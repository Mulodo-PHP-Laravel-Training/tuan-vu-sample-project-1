/**
 * Created by Tuan on 9/1/15.
 */
(function(){

    var app = angular.module('mulodoApp', ['ngRoute', 'angularUtils.directives.dirPagination', 'ngMessages']);
    var api = 'http://api.mulodo.dev';

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
        var compareTo = function() {
            return {
                require: "ngModel",
                scope: {
                    otherModelValue: "=compareTo"
                },
                link: function(scope, element, attributes, ngModel) {
                    ngModel.$validators.compareTo = function(modelValue) {
                        return modelValue == scope.otherModelValue;
                    };

                    scope.$watch("otherModelValue", function() {
                        ngModel.$validate();
                    });
                }
            };
        };
        app.directive("compareTo", function() {
            return {
                restrict: 'A',
                scope:true,
                require: 'ngModel',
                link: function (scope, elem , attrs,control) {
                    var checker = function () {
         
                        //get the value of the first password
                        var e1 = scope.$eval(attrs.ngModel); 
         
                        //get the value of the other password  
                        var e2 = scope.$eval(attrs.passwordMatch);
                        return e1 == e2;
                    };
                    scope.$watch(checker, function (n) {
         
                        //set the form control to valid if both 
                        //passwords are the same, else invalid
                        control.$setValidity("unique", n);
                    });
                }
            };
        });

        $scope.submitForm = function(isValid) {
            arlet('our form is amazing');
        };
    });

})();

