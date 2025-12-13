/**
 * JGN Admin Panel - Discord Bot
 * Handles /profile and /lookup slash commands
 * 
 * Usage: node bot.js
 * 
 * Required environment variables:
 * - DISCORD_BOT_TOKEN
 * - DISCORD_GUILD_ID
 * - PANEL_API_URL (e.g., https://staff.jgn.gg)
 */

const { Client, GatewayIntentBits, SlashCommandBuilder, EmbedBuilder, REST, Routes } = require('discord.js');
require('dotenv').config();

const client = new Client({
    intents: [GatewayIntentBits.Guilds]
});

const PANEL_API_URL = process.env.PANEL_API_URL || 'https://staff.jgn.gg';
const BOT_TOKEN = process.env.DISCORD_BOT_TOKEN;
const GUILD_ID = process.env.DISCORD_GUILD_ID;

// Validate required environment variables
if (!BOT_TOKEN) {
    console.error('ERROR: DISCORD_BOT_TOKEN is required. Set it in your .env file.');
    process.exit(1);
}

if (!GUILD_ID) {
    console.error('ERROR: DISCORD_GUILD_ID is required. Set it in your .env file.');
    process.exit(1);
}

// Slash commands definition
const commands = [
    new SlashCommandBuilder()
        .setName('profile')
        .setDescription('View your own player profile'),

    new SlashCommandBuilder()
        .setName('lookup')
        .setDescription('Look up a player by name or identifier (Staff only)')
        .addStringOption(option =>
            option.setName('query')
                .setDescription('Player name or identifier (steam:xxx, discord:xxx, etc.)')
                .setRequired(true)
        ),

    new SlashCommandBuilder()
        .setName('stats')
        .setDescription('View server statistics (Staff only)')
].map(command => command.toJSON());

// Register slash commands
async function registerCommands() {
    const rest = new REST({ version: '10' }).setToken(BOT_TOKEN);

    try {
        console.log('Registering slash commands...');
        await rest.put(
            Routes.applicationGuildCommands(client.user.id, GUILD_ID),
            { body: commands }
        );
        console.log('Slash commands registered!');
    } catch (error) {
        console.error('Failed to register commands:', error);
    }
}

// Fetch player from panel API
async function fetchPlayer(query, discordId = null) {
    try {
        let url;
        if (discordId) {
            url = `${PANEL_API_URL}/api/v1/players/by-discord/${discordId}`;
        } else {
            url = `${PANEL_API_URL}/api/v1/players/lookup?q=${encodeURIComponent(query)}`;
        }

        const response = await fetch(url, {
            headers: {
                'X-Bot-Token': BOT_TOKEN
            }
        });

        if (!response.ok) {
            return null;
        }

        return await response.json();
    } catch (error) {
        console.error('API fetch error:', error);
        return null;
    }
}

// Create player embed
function createPlayerEmbed(player, isOwnProfile = false) {
    const embed = new EmbedBuilder()
        .setColor(player.is_banned ? 0xFF0000 : (player.trust_score >= 75 ? 0x00FF00 : (player.trust_score >= 50 ? 0xFFFF00 : 0xFF0000)))
        .setTitle(`👤 ${isOwnProfile ? 'Your Profile' : player.name}`)
        .setDescription(player.is_banned ? '🚫 **This player is currently banned**' : '')
        .addFields(
            { name: '🏆 Trust Score', value: `${player.trust_score}%`, inline: true },
            { name: '⏱️ Playtime', value: player.playtime_formatted, inline: true },
            { name: '📅 First Joined', value: player.first_joined_at || 'Unknown', inline: true },
            { name: '👁️ Last Seen', value: player.last_seen_at || 'Unknown', inline: true },
            { name: '\u200B', value: '\u200B', inline: false },
            { name: '⚠️ Warnings', value: `${player.warnings_count}`, inline: true },
            { name: '👢 Kicks', value: `${player.kicks_count}`, inline: true },
            { name: '🚫 Bans', value: `${player.bans_count}`, inline: true },
            { name: '👍 Commends', value: `${player.commendations_count}`, inline: true }
        )
        .setFooter({ text: 'JGN Admin Panel' })
        .setTimestamp();

    return embed;
}

// Handle slash commands
client.on('interactionCreate', async interaction => {
    if (!interaction.isChatInputCommand()) return;

    const { commandName } = interaction;

    try {
        if (commandName === 'profile') {
            await interaction.deferReply({ ephemeral: true });

            const result = await fetchPlayer(null, interaction.user.id);

            if (!result || !result.found) {
                await interaction.editReply({
                    content: '❌ No player profile found linked to your Discord account. Make sure you\'ve connected to the FiveM server at least once with Discord linked.',
                    ephemeral: true
                });
                return;
            }

            const embed = createPlayerEmbed(result.player, true);
            await interaction.editReply({ embeds: [embed], ephemeral: true });
        }

        else if (commandName === 'lookup') {
            await interaction.deferReply();

            const query = interaction.options.getString('query');
            const result = await fetchPlayer(query);

            if (!result || !result.found) {
                await interaction.editReply({
                    content: `❌ No player found matching: \`${query}\``
                });
                return;
            }

            const embed = createPlayerEmbed(result.player);
            embed.setURL(`${PANEL_API_URL}/players/${result.player.id}`);

            await interaction.editReply({ embeds: [embed] });
        }

        else if (commandName === 'stats') {
            await interaction.deferReply();

            const embed = new EmbedBuilder()
                .setColor(0x5865F2)
                .setTitle('📊 Server Statistics')
                .setDescription('Coming soon!')
                .setFooter({ text: 'JGN Admin Panel' })
                .setTimestamp();

            await interaction.editReply({ embeds: [embed] });
        }
    } catch (error) {
        console.error('Command error:', error);

        try {
            const errorMessage = '❌ An error occurred while processing this command. Please try again later.';
            if (interaction.deferred || interaction.replied) {
                await interaction.editReply({ content: errorMessage });
            } else {
                await interaction.reply({ content: errorMessage, ephemeral: true });
            }
        } catch (e) {
            console.error('Failed to send error response:', e);
        }
    }
});

// Bot ready
client.once('ready', async () => {
    console.log(`Discord bot logged in as ${client.user.tag}`);
    await registerCommands();
});

// Login
client.login(BOT_TOKEN);
