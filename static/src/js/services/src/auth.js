angular.module('services.auth', []).service('AuthSvc', function ($q, $http, $window) {
    var self = {};

    self.isLogged = function () {
        return true;
    };

    self.login = function (username, password) {

    };

    self.logout = function () {

    };

    return self;
})