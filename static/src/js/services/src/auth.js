angular.module('services.auth', []).service('AuthSvc', ['$q', '$http', '$rootScope', 'url', '$location', function ($q, $http, $rootScope, url, $location) {
    var self = {};

    self.isLogged = function () {
        var defer = $q.defer();

        $http.get(url + 'users/auth', {
            login: window.localStorage.getItem('login') || ''
        }).then(function (res) {
           if (res.data.status) {
               $rootScope.logged = res.data.results[0];
               defer.resolve(res.data.results[0]);
           } else {
               $rootScope.logged = false;
               defer.resolve(false);
           }
        });

        return defer.promise;
    };

    self.login = function (username, password, register) {
        var defer = $q.defer();

        $http.post(url + 'users/auth', {username: username, password: password, register: register}).then(function (res) {
            if (res.data.status == false) {
                window.localStorage.removeItem('Token');
            } else {
                window.localStorage['Token'] = res.data.results[0];
            }

            defer.resolve(res.data.status);
        });

        return defer.promise;
    };

    self.logout = function () {
        window.localStorage.removeItem('Token');
        $location.path('/');
    };

    return self;
}]);