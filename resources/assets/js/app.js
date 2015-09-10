/**
 * Created by Tuan on 9/1/15.
 */


(function () {
    'use strict';

    angular.module('mulodoApp', [
        'ngRoute',
        'angularUtils.directives.dirPagination',
        'ngMessages',
        'ngStorage'
    ])
        .constant('config', {
            BASE_API: 'http://api.mulodo.dev',
            BASE_AUTH: 'http://api.mulodo.dev/auth'
        })
        .directive('navBar', function () {
            return {
                restrict: 'E',
                templateUrl: 'layout/navbar.html'
            };
        })
        .directive('footer', function () {
            return {
                restrict: 'E',
                templateUrl: 'layout/footer.html'
            };
        })
        .config(['$routeProvider', '$httpProvider', function ($routeProvider, $httpProvider) {

            $routeProvider.
                when('/', {
                    templateUrl: 'layout/home.html',
                    controller: 'HomeController'
                }).
                when('/signin', {
                    templateUrl: 'auth/login.html',
                    controller: 'HomeController'
                }).
                when('/user', {
                    templateUrl: 'user/index.html',
                    controller: 'userListController'
                }).
                when('/user/create', {
                    templateUrl: 'user/form.html',
                    controller: 'userCreateController'
                }).
                when('/user/edit', {
                    templateUrl: 'user/form.html',
                    controller: 'userEditController'
                }).
                otherwise({
                    redirectTo: '/'
                });

            $httpProvider.interceptors.push(['$q', '$location', '$localStorage', function ($q, $location, $localStorage) {
                return {
                    'request': function (config) {
                        config.headers = config.headers || {};
                        if ($localStorage.token) {
                            config.headers.Authorization = 'Bearer ' + $localStorage.token;
                        }
                        return config;
                    },
                    'responseError': function (response) {
                        if (response.status === 401 || response.status === 403) {
                            delete $localStorage.token;
                            $location.path('/signin');
                        }
                        return $q.reject(response);
                    }
                };
            }]);
        }])
        .run(function ($rootScope, $location, $localStorage) {
            $rootScope.$on("$routeChangeStart", function (event, next) {
                if ($localStorage.token == null) {
                    if (next.access != 'layout/home.html') {
                        $location.path("/signin");
                    }
                }
            });
        })
        .controller('HomeController', ['$rootScope', '$scope', '$location', '$localStorage', 'Auth',
            function ($rootScope, $scope, $location, $localStorage, Auth) {
                function successAuth(res) {
                    $localStorage.token = res.token;
                    window.location = "/app";
                }

                $scope.signin = function () {
                    var formData = {
                        email: $scope.email,
                        password: $scope.password
                    };

                    Auth.signin(formData, successAuth, function () {
                        $rootScope.error = 'Invalid Credentials';
                    });

                };

                $scope.signup = function () {
                    var formData = {
                        email: $scope.email,
                        password: $scope.password
                    };

                    Auth.signup(formData, successAuth, function (res) {
                        $rootScope.error = res.error || 'Failed to sign up.';
                    });
                };

                $scope.signout = function () {
                    Auth.signout(function () {
                        window.location = "/app";
                    });
                };
                $scope.token = $localStorage.token;
                $scope.tokenClaims = Auth.getTokenClaims();
            }])
        .controller('userListController', function ($scope, $http, $route, config) {
            $scope.users = [];
            $scope.total = 60;
            $scope.perPage = 5;
            getResultsPage(1);

            $scope.pagination = {
                current: 1
            };

            function getResultsPage(pageNumber) {
                $http.get(config.BASE_API + '/user?page=' + pageNumber + '&limit=' + $scope.perPage)
                    .then(function (result) {
                        $scope.users = result.data.data;
                        $scope.total = result.data.total;
                    });
            }

            $scope.pageChanged = function (newPage) {
                getResultsPage(newPage);
            };

            $scope.deleteUser = function (id) {
                $http.delete(config.BASE_API + '/user/' + id)
                    .success(function () {
                        alert('Delete user successful!');
                        $route.reload();
                    });
            };
        })
        .controller('userCreateController', function ($scope, $http, $httpParamSerializer, $location, config) {
            $scope.addUser = function (user) {
                $http.post(config.BASE_API + '/user', $httpParamSerializer($scope.user), {
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function () {
                    alert('Add new user successful!');
                    $location.path("/user");
                }).error(function () {
                    alert('Error!');
                });
            };
        })
        .factory('Auth', ['$http', '$localStorage', 'config', function ($http, $localStorage, config) {
            function urlBase64Decode(str) {
                var output = str.replace('-', '+').replace('_', '/');
                switch (output.length % 4) {
                    case 0:
                        break;
                    case 2:
                        output += '==';
                        break;
                    case 3:
                        output += '=';
                        break;
                    default:
                        throw 'Illegal base64url string!';
                }
                return window.atob(output);
            }

            function getClaimsFromToken() {
                var token = $localStorage.token;
                var user = {};
                if (typeof token !== 'undefined') {
                    var encoded = token.split('.')[1];
                    user = JSON.parse(urlBase64Decode(encoded));
                }
                return user;
            }

            var tokenClaims = getClaimsFromToken();

            return {
                signup: function (data, success, error) {
                    $http.post(config.BASE_AUTH + '/signin', data).success(success).error(error);
                },
                signin: function (data, success, error) {
                    $http.post(config.BASE_AUTH + '/signin', data).success(success).error(error);
                },
                signout: function (success) {
                    tokenClaims = {};
                    delete $localStorage.token;
                    success();
                },
                getTokenClaims: function () {
                    return tokenClaims;
                }
            };
        }]);

})();