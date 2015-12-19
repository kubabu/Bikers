var base = document.createElement('base');

if (window.location.origin !== 'null') {
    base.setAttribute('href', window.location.origin + window.location.pathname);
} else {
    base.setAttribute('href', window.location.protocol + '//' + window.location.pathname);
}

document.getElementsByTagName('head')[0].appendChild(base);

var app = angular.module('bikersApp', ['ngRoute', 'services', 'controllers']);

app.constant('url', 'http://localhost/bikers/api/v1/'); //default api path

app.config(function ($routeProvider, $locationProvider, $httpProvider) {
    var resolve = {
        //token: function ($q) {
        //    var defer = $q.defer();
        //
        //    $httpProvider.defaults.headers.common.Token = window.localStorage['Token'] || 'test';
        //    defer.resolve(true);
        //
        //    return $defer.promise;
        //},
        auth: function ($q, AuthSvc, $location) {
            var defer = $q.defer();

            AuthSvc.isLogged().then(function (logged) {
                if (!logged) {
                    $location.path('/login/');
                    defer.resolve(true);
                } else {
                    defer.resolve(true);
                }
            });

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
        .when('/:controller/', {
            templateUrl: function (params) {
                return 'src/templates/' + params.controller + '/main.html';
            },
            resolve: resolve
        })
        .when('/:controller/:action/', {
            templateUrl: function (params) {
                return 'src/templates/' + params.controller + '/' + params.action + '.html';
            },
            resolve: resolve
        })
        .otherwise({redirectTo:'/'});
});