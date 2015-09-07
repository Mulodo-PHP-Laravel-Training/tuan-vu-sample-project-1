/**
 * Created by Tuan on 9/1/15.
 */
(function(){

    var app = angular.module('mulodoApp', ['ngRoute', 'angularUtils.directives.dirPagination', 'ngMessages']);
    var api = 'http://api.mulodo.dev';

    app.directive('navBar', function() {
        return {
            restrict: 'E',
            templateUrl: 'layout/navbar.html'
        };
    });

    app.directive('footer', function() {
        return {
            restrict: 'E',
            templateUrl: 'layout/footer.html'
        };
    });

    app.config(['$routeProvider', function($routeProvider) {
        $routeProvider.
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
                redirectTo: ''
            });
    }]);

    app.controller('userListController', function ($scope, $http) {
        $scope.users = [];
        $scope.total = 60;
        $scope.perPage = 5;
        getResultsPage(1);

        $scope.pagination = {
            current: 1
        };

        $scope.pageChanged = function(newPage) {
            getResultsPage(newPage);
        };

        function getResultsPage(pageNumber) {
            $http.get(api + '/user?page=' + pageNumber + '&limit=' + $scope.perPage)
                .then(function(result) {
                    $scope.users = result.data.data;
                    $scope.total = result.data.total;
                });
        }
    });

    app.controller('userCreateController', function($scope) {
        $scope.submitForm = function(isValid) {
            arlet('our form is amazing');
        };
    });

    app.directive("ngMatch", ['$parse', function($parse) {
            var directive = {
                link: link,
                restrict: 'A',
                require: '?ngModel'
            };
            return directive;

            function link(scope, elem, attrs, ctrl) {
                if(!ctrl) return;
                if(!attrs.ngMatch) return;

                var firstPassword = $parse(attrs.ngMatch);

                var validator = function(value) {
                    var temp = firstPassword(scope),
                    v = value === temp;
                    ctrl.$setValidity('match', v);
                    return value;
                };
            }
            ctrl.$parseers.unshift(validator);
            ctrl.$formatters.push(validator);
            attrs.$observe('ngMatch', function() {
                validator(ctrl.$viewValue);
            });
        }]);
})();

