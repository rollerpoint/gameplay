Andew Kucherenko 
mailto: rollerpoint@i.ua

Scan report for ruvod.com & mooscle.com (188.246.233.180)
Host is up (0.59s latency).
rDNS record for 188.246.233.180: mainbeyond.club
Not shown: 764 filtered ports, 231 closed ports
PORT     STATE SERVICE VERSION
22/tcp   open  ssh     OpenSSH 7.2p2 Ubuntu 4ubuntu2.4 (Ubuntu Linux; protocol 2.0)
| ssh-hostkey: 
|   2048 8a:79:f3:23:64:3e:01:18:b5:3e:d1:73:3f:57:1d:e0 (RSA)
|   256 f4:5c:81:49:45:e4:5d:75:51:c2:cf:92:4b:b5:d4:74 (ECDSA)
|_  256 5b:30:4d:82:4e:03:54:0f:f9:89:f2:ef:2a:1c:26:50 (EdDSA)
53/tcp   open  domain  ISC BIND none
| dns-nsid: 
|_  bind.version: none
80/tcp   open  http    Apache httpd 2.4.18
|_http-server-header: Apache/2.4.18 (Ubuntu)
|_http-title: Did not follow redirect to https://mooscle.com/
443/tcp  open  ssl/ssl Apache httpd (SSL-only mode)
| http-robots.txt: 1 disallowed entry 
|_/wp-admin/
|_http-title: 400 Bad Request
| ssl-cert: Subject: commonName=mooscle.com
| Subject Alternative Name: DNS:mooscle.com
| Not valid before: 2018-04-23T19:26:49
|_Not valid after:  2018-07-22T19:26:49
|_ssl-date: TLS randomness does not represent time
3306/tcp open  mysql   MySQL (unauthorized)
Aggressive OS guesses: Linux 3.10 - 4.8 (94%), Linux 3.13 (94%), Linux 3.13 or 4.2 (94%), Linux 4.4 (94%), Linux 3.16 (92%), Linux 3.16 - 4.6 (92%), Linux 3.12 (90%), Linux 3.18 (90%), Linux 3.2 - 4.8 (90%), Linux 3.8 - 3.11 (90%)
No exact OS matches for host (test conditions non-ideal).
Service Info: Host: ruvod.com; OS: Linux; CPE: cpe:/o:linux:linux_kernel
