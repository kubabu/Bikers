angular.module('services.auth', []).service('AuthSvc', ['$q', '$http', '$rootScope', 'url', '$location', function ($q, $http, $rootScope, url, $location) {
    var self = {};

    self.isLogged = function () {
        var defer = $q.defer();

        //$http.get(url + 'users/auth').then(function (res) {
        //   if (res.data.status) {
        //       $rootScope.logged = true;
        //       defer.resolve(true);
        //   } else {
        //       $rootScope.logged = false;
        //       defer.resolve(true);
        //   }
        //});

        $rootScope.logged = true;
        defer.resolve(true);

        return defer.promise;
    };

    self.login = function (login, password) {
        var defer = $q.defer();

        $http.post(url + '/users/auth', {login: login, password: password}).then(function (res) {
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