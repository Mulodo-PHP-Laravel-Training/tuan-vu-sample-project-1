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
            }]);

})();