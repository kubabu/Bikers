var app = angular.module('bikersApp', ['ngRoute', 'services', 'controllers']);

angular.element(document.getElementsByTagName('head')).append(angular.element('<base href="' + window.location.pathname + '" />')); //base url required for proper routing

app.constant('url', 'localhost/bikers/api/v1/'); //default api path

app.config(function ($routeProvider, $locationProvider, $httpProvider) {
    var resolve = {
        auth: function ($q, AuthSvc, $location) {
            var defer = $q.defer();

            AuthSvc.isLogged().then(function (logged) {
                if (!logged) {
                    $location.path('/login/');
                    defer.resolve(false);
                } else {
                    defer.resolve(true);
                }
            });

            return defer.promise;
        }
    }; //check auth before any routing

    $httpProvider.defaults.headers.common.Token = window.localStorage['Token'] || '';

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