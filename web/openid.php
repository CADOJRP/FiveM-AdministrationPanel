<?php
class LightOpenID {
    public $returnUrl, $required = array(), $optional = array(), $verify_peer = null, $capath = null, $cainfo = null, $data;
    private $identity, $claimed_id;
    protected $server, $version, $trustRoot, $aliases, $identifier_select = false, $ax = false, $sreg = false, $setup_url = null, $headers = array();
    static protected $ax_to_sreg = array(
        'namePerson/friendly'     => 'nickname',
        'contact/email'           => 'email',
        'namePerson'              => 'fullname',
        'birthDate'               => 'dob',
        'person/gender'           => 'gender',
        'contact/postalCode/home' => 'postcode',
        'contact/country/home'    => 'country',
        'pref/language'           => 'language',
        'pref/timezone'           => 'timezone',
    );

    function __construct($host) {
        $this->trustRoot = (strpos($host, '://') ? $host : 'http://' . $host);
        if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) {
            $this->trustRoot = (strpos($host, '://') ? $host : 'https://' . $host);
        }
        if ((!isset($_SERVER['SERVER_PORT']) || !$_SERVER['SERVER_PORT'] || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 80)) && empty($_SERVER['HTTPS'])) {
            $this->trustRoot = (strpos($host, '://') ? $host : 'http://' . $host);
        }
        $this->returnUrl = $this->trustRoot . $_SERVER['REQUEST_URI'];
        $this->data = ($_SERVER['REQUEST_METHOD'] === 'POST') ? $_POST : $_GET;
        if (!function_exists('curl_init')) {
            throw new ErrorException('curl extension needed');
        }
    }

    function __set($name, $value) {
        switch ($name) {
            case 'identity':
                if (strlen($value = trim((String) $value))) {
                    if (preg_match('#^xri:/*#i', $value, $m)) {
                        $value = substr($value, strlen($m[0]));
                    } elseif (!preg_match('/^(?:[=@+\$!\(]|https?:)/i', $value)) {
                        $value = 'http://' . $value;
                    }
                    if (preg_match('#^https?://[^/]+$#i', $value, $m)) {
                        $value .= '/';
                    }
                }
                $this->$name = $this->claimed_id = $value;
                break;
            case 'trustRoot':
            case 'realm':
                $this->trustRoot = trim($value);
        }
    }

    function __get($name) {
        switch ($name) {
            case 'identity':
                return $this->claimed_id;
            case 'trustRoot':
            case 'realm':
                return $this->trustRoot;
            case 'mode':
                return empty($this->data['openid_mode']) ? null : $this->data['openid_mode'];
        }
    }

    function authUrl() {
        $params = array(
            'openid.ns'         => 'http://specs.openid.net/auth/2.0',
            'openid.mode'       => 'checkid_setup',
            'openid.return_to'  => $this->returnUrl,
            'openid.realm'      => $this->trustRoot,
            'openid.identity'   => $this->identity,
            'openid.claimed_id' => $this->claimed_id,
        );
        if (!$this->ax && !$this->sreg) {
            $params['openid.ns.ax'] = 'http://openid.net/srv/ax/1.0';
            $params['openid.ax.mode'] = 'fetch_request';
            $this->ax = true;
            $params['openid.ax.required'] = 'namePerson,favorableName,contact/email';
            $params['openid.ax.type.namePerson'] = 'http://axschema.org/namePerson';
            $params['openid.ax.type.favorableName'] = 'http://axschema.org/namePerson/friendly';
            $params['openid.ax.type.contact/email'] = 'http://axschema.org/contact/email';
        }
        return $this->build_url(parse_url($this->server), array('query' => http_build_query($params, '', '&')));
    }

    function validate() {
        $this->claimed_id = isset($this->data['openid_claimed_id']) ? $this->data['openid_claimed_id'] : $this->data['openid_identity'];
        $params = array(
            'openid.assoc_handle' => isset($this->data['openid_assoc_handle']) ? $this->data['openid_assoc_handle'] : '',
            'openid.signed'       => $this->data['openid_signed'],
            'openid.sig'          => $this->data['openid_sig'],
            'openid.ns'           => 'http://specs.openid.net/auth/2.0',
        );
        if (isset($this->data['openid_ns'])) {
            $params['openid.ns'] = $this->data['openid_ns'];
        }
        $params['openid.mode'] = 'check_authentication';
        $params['openid.op_endpoint'] = $this->data['openid_op_endpoint'];
        $signed = explode(',', $this->data['openid_signed']);
        foreach ($signed as $item) {
            $val = isset($this->data['openid_' . str_replace('.', '_', $item)]) ? $this->data['openid_' . str_replace('.', '_', $item)] : '';
            $params['openid.' . $item] = $val;
        }
        $params['openid.claimed_id'] = $this->claimed_id;
        $params['openid.identity'] = $this->data['openid_identity'];
        $params['openid.return_to'] = $this->returnUrl;
        $response = $this->request($this->data['openid_op_endpoint'], 'POST', $params);
        return preg_match('/is_valid\s*:\s*true/i', $response);
    }

    protected function request($url, $method = 'GET', $params = array()) {
        $curl = curl_init($url . ($method == 'GET' && !empty($params) ? '?' . http_build_query($params, '', '&') : ''));
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/xrds+xml, */*'));
        if ($this->verify_peer !== null) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->verify_peer);
        }
        if ($this->capath) {
            curl_setopt($curl, CURLOPT_CAPATH, $this->capath);
        }
        if ($this->cainfo) {
            curl_setopt($curl, CURLOPT_CAINFO, $this->cainfo);
        }
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params, '', '&'));
        }
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            throw new ErrorException(curl_error($curl), curl_errno($curl));
        }
        curl_close($curl);
        return $response;
    }

    protected function build_url($url, $parts) {
        $url = $url['scheme'] . '://' . (isset($url['user']) ? $url['user'] . (isset($url['pass']) ? ':' . $url['pass'] : '') . '@' : '') . $url['host'] . (isset($url['port']) && $url['port'] != (isset($url['scheme']) && $url['scheme'] == 'https' ? 443 : 80) ? ':' . $url['port'] : '') . (isset($url['path']) ? $url['path'] : '/') . (isset($url['query']) ? '?' . $url['query'] : '') . (isset($url['fragment']) ? '#' . $url['fragment'] : '');
        if (isset($parts['query'])) {
            $url .= (strpos($url, '?') ? '&' : '?') . $parts['query'];
        }
        return $url;
    }
}
?>
