#!/bin/bash

PATH='/home/matpif/Workspace/'
PROJECT='Paris'
SERVER='matpif@matpif.xyz'
PORT=22222

cd $PATH
/bin/tar -zcvf $PROJECT.tar.gz $PROJECT
/usr/bin/scp -P$PORT $PROJECT.tar.gz matpif@matpif.xyz:/home/matpif/.
/usr/bin/ssh -P$PORT $SERVER 'tar -zxvf $PROJECT.tar.gz'
/usr/bin/ssh -P$PORT $SERVER 'sudo rm -rf /var/www/paris'
/usr/bin/ssh -P$PORT $SERVER 'sudo mv $PROJECT /var/www/.'
/usr/bin/ssh -P$PORT $SERVER 'sudo mv /var/www/$PROJECT /var/www/paris'
/usr/bin/ssh -P$PORT $SERVER 'sudo rm -rf $PROJECT.tar.gz'