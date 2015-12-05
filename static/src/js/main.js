var app = angular.module('bikersApp', ['ngRoute', 'services', 'controllers']);

angular.element(document.getElementsByTagName('head')).append(angular.element('<base href="' + window.location.pathname + '" />')); //base url required for proper routing

app.config(function ($routeProvider, $locationProvider) {
    var resolve = {
        auth: function ($q, AuthSvc, $location) {
            var defer = $q.defer();

            if (!AuthSvc.isLogged()) {
                $location.path('/login/');
                defer.resolve(false);
            } else {
                defer.resolve(true);
            }

            return defer.promise;
        }
    }; //check auth before any routing

    $routeProvider
        .when('/', {
            templateUrl: 'src/templates/desktop/main.html',
            resolve: resolve
        })
        .when('/login/', {
            templateUrl: 'src/templates/desktop/login.html'
        })
        .when('/:controller/:action/', {
            templateUrl: function (params) {
                return 'src/templates/' + params.controller + '/' + params.action + '.html';
            },
            resolve: resolve
        })
        .when('/:controller/', {
            templateUrl: function (params) {
                return 'src/templates/' + params.controller + '.html';
            },
            resolve: resolve
        })
        .otherwise({redirectTo:'/'});

    $locationProvider.html5Mode(true); //nice html5 urls and browser history support
});