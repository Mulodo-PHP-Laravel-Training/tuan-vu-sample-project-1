/**
 * Created by tuan on 9/9/15.
 */
(function () {
    'use strict';

    var app = angular.module('mulodoApp');

    app.controller('userListController', function ($scope, $http, $route) {
        $scope.users = [];
        $scope.total = 60;
        $scope.perPage = 5;
        getResultsPage(1);

        $scope.pagination = {
            current: 1
        };

        $scope.pageChanged = function (newPage) {
            getResultsPage(newPage);
        };

        function getResultsPage(pageNumber) {
            $http.get(api + '/user?page=' + pageNumber + '&limit=' + $scope.perPage)
                .then(function (result) {
                    $scope.users = result.data.data;
                    $scope.total = result.data.total;
                });
        }

        $scope.deleteUser = function (id) {
            $http.delete(api + '/user/' + id)
                .success(function () {
                    alert('Delete user successful!');
                    $route.reload();
                });
        };
    });

    app.controller('userCreateController', function ($scope, $http, $httpParamSerializer, $location) {
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