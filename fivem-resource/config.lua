--[[
    FiveM Admin Panel Configuration
    https://staff.jgn.gg
    
    Edit the values below to connect to your panel.
]]

Config = {}

-- Panel API URL (change this to your panel URL)
Config.PanelURL = "https://staff.jgn.gg"

-- Server Authentication Token
-- Get this from: Panel → Servers → Add Server → Copy Token
Config.ServerToken = "YOUR_SERVER_TOKEN_HERE"

-- Server Name (displayed in panel)
Config.ServerName = "Main Server"

-- Identifiers to collect from players
-- Recommended: Keep all enabled for best ban evasion prevention
Config.Identifiers = {
    fivem = true,      -- Cfx.re ID (most secure)
    discord = true,    -- Discord ID (very secure)
    license2 = true,   -- Rockstar license (Steam)
    license = true,    -- Rockstar Social Club
    steam = true,      -- Steam Hex ID
    xbl = false,       -- Xbox Live (optional)
    ip = false         -- IP address (optional, privacy concern)
}

-- Heartbeat interval (seconds)
-- How often to update server status in panel
Config.HeartbeatInterval = 60

-- Show join messages with trust score
Config.ShowJoinMessages = true

-- Trust score message format
-- {name} = player name, {score} = trust score percentage
Config.JoinMessageFormat = "^3{name}^0 joined with ^2{score}%^0 trust score."

-- Enable in-game admin commands
Config.EnableCommands = true

-- Debug mode (prints extra info to console)
Config.Debug = false
