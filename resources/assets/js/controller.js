/**
 * Created by tuan on 9/9/15.
 */


(function () {
    'use strict';

    angular.module('mulodoApp')
        .controller('HomeController', ['$rootScope', '$scope', '$location', '$localStorage', 'Auth',
            function ($rootScope, $scope, $location, $localStorage, Auth) {
                function successAuth(res) {
                    $localStorage.token = res.token;
                    $location.path('/');
                }

                $scope.signin = function () {
                    var formData = {
                        email: $scope.email,
                        password: $scope.password
                    };

                    Auth.signin(formData, successAuth, function () {
                        $rootScope.error = 'Invalid credentials';
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

                $scope.logout = function () {
                    Auth.logout(function () {
                        $location.path('/');
                    });
                };
                $scope.token = $localStorage.token;
                $scope.tokenClaims = Auth.getTokenClaims();
            }])
        .controller('AuthController', function () {

        })
        .controller('userListController', function ($scope, $http, $route) {
            $scope.users = [];
            $scope.total = 60;
            $scope.perPage = 5;
            getResultsPage(1);

            $scope.pagination = {
                current: 1
            };

            function getResultsPage(pageNumber) {
                $http.get(api + '/user?page=' + pageNumber + '&limit=' + $scope.perPage)
                    .then(function (result) {
                        $scope.users = result.data.data;
                        $scope.total = result.data.total;
                    });
            }

            $scope.pageChanged = function (newPage) {
                getResultsPage(newPage);
            };

            $scope.deleteUser = function (id) {
                $http.delete(api + '/user/' + id)
                    .success(function () {
                        alert('Delete user successful!');
                        $route.reload();
                    });
            };
        })
        .controller('userCreateController', function ($scope, $http, $httpParamSerializer, $location) {
            $scope.addUser = function (user) {
                $http.post(api + '/user', $httpParamSerializer($scope.user), {
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function () {
                    alert('Add new user successful!');
                    $location.path("/user");
                }).error(function () {
                    alert('Error!');
                });
            };
        });

})();