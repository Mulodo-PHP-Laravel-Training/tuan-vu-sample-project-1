CMS Example is small Single-page application building in Laravel 5.1 and Angularjs . It's very simple app with 3 modules: users, posts, comments.

## Installation

1.Please install Laravel/Homestead before download this app.

2.Please create virtual host name api.app and edit hometead config file homestead.yaml:

    sites:
    - map: api.mulodo.app
    to: /home/vagrant/Code/api.mulodo.dev/public

3.Create db : blog with below information:

    DB   : blog
    User : homestead
    Pass : secret

4.Run cmd migrate to create and insert databse:

    $ php artisan migrate
    $ php artisan db:seed

## Usage

1. User Module:

1.1 Create User:

    Method: POST
    Link  : http://api.app/api/users
    Params:
      + username: anh.tuan
      + password: 123456
      + first_name: Tuan
      + last_name: Nguyen
      + email: anh.tuan@mulodo.com
1.2 Login User:

    Method: POST
    Link: http://api.app/api/users/login
    Params:
      + username: anh.tuan
      + password: 123456

1.3 Logout User:

    Method: GET
    Link: http://api.app/api/users/logout?token=Gb1AKNWBgkxLeiedPK4ktYKP9aQW8xi6iHJjmEsihNxjRBVpZ06P9N2eMGcn

1.4 Get User Info:

    Method: GET
    Link: http://api.app/api/users?token=AO9k97YN58rGHmmTprClNKfXlpUCaGTO7pixyeSOfn40OXPqpc95mdQCszDy

1.5 Update User Info:

    Method: PUT
    Link: http://api.app/api/users/{$id}
    Params:
      + username: anh.tuan2
      + password: 123456
      + first_name: Tung
      + last_name: Lam
      + email: anh.tuan2@mulodo.com
      

## Contributing

## History

Version 1.0