# Netcup DNS Certbot Hooks

Authentication and cleanup hooks to facilitate obtaining and automatically 
renewing certificates for (wildcard) domains hosted by and using [netcup][2]
DNS name servers with [Certbot][1] e ACME dns-01

The Authentication Hook will automatically deploy the DNS record with the
challenge token via the [netcup DNS API][3].


## Install

### Prerequisites

These hooks work for domains hosted with netcup that also use
netcup's name servers (which is the default). You need to enable Domain
API access in the netcup CCP and create an API user.

The server needs to have the following installed: 
  * Certbot
  * PHP (cli) with soap extension
  * composer
  * git

### Setup

Clone the repo and install dependencies using composer:

    git clone https://github.com/daiwai/netcup-certbot-dns netcup-certbot-dns
    cd netcup-certbot-dns
    composer install
    
Then edit ```conifg.php``` in the netcup-certbot-dns root folder. You need to
enter your netcup API key, API pass, and customer ID:

    cp config-dist.php config.php
    vim config.php
   
    
## Usage

Pass the hooks to certbot when requesting a certificate for your domain:

    certbot certonly --manual --preferred-challenges=dns \
    --server https://acme-v02.api.letsencrypt.org/directory \
    --manual-auth-hook 'netcup-dns/bin/auth.php' \
    --manual-cleanup-hook 'netcup-dns/bin/cleanup.php' \
    --manual-public-ip-logging-ok \
    -d '*.example.com'

[1]: https://certbot.eff.org/
[2]: https://www.netcup.de/
[3]: https://www.netcup-wiki.de/wiki/DNS_API
