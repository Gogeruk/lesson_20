//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\
OGEY
Have fun I guess?
G̶o̶d̶ ̶h̶a̶v̶e̶ ̶m̶e̶r̶c̶y̶ ̶o̶n̶ ̶y̶o̶u̶r̶ ̶s̶o̶u̶l̶

>>>>>>>>>>>>>>> INFO

// FILES OF INTEREST
www/database/*
www/routes/api.php
www/app/Models/*
www/app/Http/Controllers/*
www/app/Http/Resources/*
www/app/Jobs/ProcessUser.php


// VAGRANT INFO
Vagrant 2.2.14

// SYSTEM INFO
Distributor ID:	Kali
Description:	Kali GNU/Linux Rolling
Release:	2021.1
Codename:	kali-rolling

>>>>>>>>>>>>>>> TESTING

1.
// go to yoour laravel dir

2.
// change .env
HERE IS MINE:
/////////////////////////////////////////////////////////////////////////////
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:z8fWgnMdC8zxTr6uOS0I85RkVAj6T/pR9ito8Irx4Xw=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sandbox
DB_USERNAME=homestead
DB_PASSWORD=secret

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
/////////////////////////////////////////////////////////////////////////////

3.
bash start.bash
(this will seed db)
(it will create
    20 users,
     40 projects,
      60 labels
       + json data from countries and continents)


/// TESTING CRUD AND FILTERS ///

/// USER ////
1.
// create a new user
curl -X POST http://les.test/api/users -H "Accept: application/json" -H "Content-Type: application/json" -d '{"name": "Bob", "email": "bob.doe@toptal.com", "password": "12345", "country": "us"}'

2.
// check `jobs` table
sudo mysql
use da_name;
SELECT * FROM `jobs`;

3.
// exec a job
artisan queue:work --queue=process_user --tries=5

// look at `jobs` and `users`
SELECT * FROM `jobs`;
SELECT * FROM `users`;

4.
// verify your user by name
curl -X PATCH http://les.test/api/users -H "Accept: application/json" -H "Content-Type: application/json" -d '{"name": "Bob"}'
!!!
COPY YOUR api_token FROM THE OUTPUT
!!!
!!!
YOU CAN ctrl+f REPLACE ALL "YOUR_TOKEN" WITH THIS TOKEN
!!!

5.
// edit
curl -X PUT http://les.test/api/users -H "Accept: application/json" -H "Content-Type: application/json" -d '{"name": "Bob222", "email": "222bob.doe@toptal.com", "password": "54321", "country": "cn", "api_token": "YOUR_TOKEN"}'

6.
// filters
curl -X GET http://les.test/api/users -H "Accept: application/json" -H "Content-Type: application/json" -d '{"name": "Bob222"}'
curl -X GET http://les.test/api/users -H "Accept: application/json" -H "Content-Type: application/json" -d '{"email": "222bob.doe@toptal.com"}'
curl -X GET http://les.test/api/users -H "Accept: application/json" -H "Content-Type: application/json" -d '{"verified": "1"}'
curl -X GET http://les.test/api/users -H "Accept: application/json" -H "Content-Type: application/json" -d '{"country": "cn"}'
curl -X GET http://les.test/api/users -H "Accept: application/json" -H "Content-Type: application/json" -d '{"name": "Bob222", "verified": "1", "country": "cn", "shit": "nugget"}'
!!!
UNAUTHORIZED FOR TESTING PURPOSES
!!!


/// PROJECT ////

7.
// create
curl -X POST http://les.test/api/projects/ -H "Accept: application/json" -H "Content-Type: application/json" -d '{"api_token": "YOUR_TOKEN", "project_name": "Project 1"}'

// check `projects` table
SELECT * FROM `projects`;

8.
// link
curl -X PUT http://les.test/api/projects/ -H "Accept: application/json" -H "Content-Type: application/json" -d '{"project_id":[10,41], "user_id":[1,2,3,4,5]}'
!!!
UNAUTHORIZED FOR TESTING PURPOSES
!!!
// check `project_user` table
SELECT * FROM `project_user`;

9.
// filer
curl -X GET http://les.test/api/projects -H "Accept: application/json" -H "Content-Type: application/json" -d '{"api_token": "YOUR_TOKEN"}'
curl -X GET http://les.test/api/projects -H "Accept: application/json" -H "Content-Type: application/json" -d '{"api_token": "YOUR_TOKEN", "email": "222bob.doe@toptal.com"}'
curl -X GET http://les.test/api/projects -H "Accept: application/json" -H "Content-Type: application/json" -d '{"api_token": "YOUR_TOKEN", "labels":[1,2,3,4,5,41]}'
curl -X GET http://les.test/api/projects -H "Accept: application/json" -H "Content-Type: application/json" -d '{"api_token": "YOUR_TOKEN", "email": "222bob.doe@toptal.com", "labels":[1,2,3,6,61]}'
curl -X GET http://les.test/api/projects -H "Accept: application/json" -H "Content-Type: application/json" -d '{"api_token": "YOUR_TOKEN", "continent": "as"}'
curl -X GET http://les.test/api/projects -H "Accept: application/json" -H "Content-Type: application/json" -d '{"api_token": "YOUR_TOKEN", "email": "222bob.doe@toptal.com", "labels":[1,2,3,6,61], "continent": "as"}'
curl -X GET http://les.test/api/projects -H "Accept: application/json" -H "Content-Type: application/json" -d '{"api_token": "YOUR_TOKEN", "labels":[1,2,3,4,5,61], "shit": "nagget", "continent": "as"}'


/// LABEL ////

10.
// create
curl -X POST http://les.test/api/labels -H "Accept: application/json" -H "Content-Type: application/json" -d '{"api_token": "YOUR_TOKEN", "label_name": "Label 1"}'
// check `labels` table
SELECT * FROM `labels`;


11.
// link
curl -X PUT http://les.test/api/labels -H "Accept: application/json" -H "Content-Type: application/json" -d '{"label_id":[1,61], "project_id":[41,1,2,3,4,5]}'
!!!
UNAUTHORIZED FOR TESTING PURPOSES
!!!
// check `label_project` table
SELECT * FROM `label_project`;

12.
// filer
curl -X GET http://les.test/api/labels -H "Accept: application/json" -H "Content-Type: application/json" -d '{"api_token": "YOUR_TOKEN"}'
curl -X GET http://les.test/api/labels -H "Accept: application/json" -H "Content-Type: application/json" -d '{"api_token": "YOUR_TOKEN", "email": "222bob.doe@toptal.com"}'
curl -X GET http://les.test/api/labels -H "Accept: application/json" -H "Content-Type: application/json" -d '{"api_token": "YOUR_TOKEN", "projects":[1,2,3,4,5,41]}'
curl -X GET http://les.test/api/labels -H "Accept: application/json" -H "Content-Type: application/json" -d '{"api_token": "YOUR_TOKEN", "email": "222bob.doe@toptal.com", "projects":[1,2,3,4,5,41]}'
curl -X GET http://les.test/api/labels -H "Accept: application/json" -H "Content-Type: application/json" -d '{"api_token": "YOUR_TOKEN", "email": "222bob.doe@toptal.com", "projects":[1,2,3,4,5,61], "shit": "nagget"}'


// DELETING //

curl -X DELETE http://les.test/api/labels/ -H "Accept: application/json" -H "Content-Type: application/json" -d '{"id_of_a_label": "61", "api_token": "YOUR_TOKEN"}'

curl -X DELETE http://les.test/api/projects/ -H "Accept: application/json" -H "Content-Type: application/json" -d '{"id_of_a_project": "41", "api_token": "YOUR_TOKEN"}'

curl -X DELETE http://les.test/api/users -H "Accept: application/json" -H "Content-Type: application/json" -d '{"api_token": "YOUR_TOKEN"}'









//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\//*\\
