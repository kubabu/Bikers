angular.module('services.auth', []).service('AuthSvc', ['$q', '$http', '$rootScope', 'url', function ($q, $http, $rootScope, url) {
    var self = {};

    /**
     * Checks if current session is logged. After changing sessions sessionStorage is empty,
     * not like localStorage, which stays the same between sessions, '
     * preventing token and username form immediate erasing
     * @private
     */
    _sessionNotLogged = function () {
        return angular.isUndefined(window.sessionStorage['isLogged']) ||
            window.sessionStorage['isLogged'] == false ||
            window.sessionStorage['isLogged'] == 'false';
    }

    self.isLogged = function () {
        var defer = $q.defer();

        if (_sessionNotLogged()) {
            $http.get(url + 'users/auth', {
                params: {
                    username: window.localStorage.getItem('username') || ''
                }
            }).then(function (res) {
                if (res.data.status) {
                    $rootScope.logged = res.data.results[0];
                    defer.resolve(res.data.results[0]);
                } else {
                    $rootScope.logged = false;
                    defer.resolve(false);
                }
            });
        } else {
            defer.resolve(true);
        }


        return defer.promise;
    };

    self.login = function (username, password, register) {
        var defer = $q.defer();

        $http.post(url + 'users/auth', {username: username, password: password, register: register}).then(function (res) {
            if (res.data.status == false || !res.data.results[0]) {
                self.logout(false);
            } else {
                window.localStorage['Token'] = res.data.results[0];
                window.localStorage['username'] = username;
            }

            defer.resolve(res.data.status);
        });

        return defer.promise;
    };

    self.logout = function (reload) {
        window.localStorage.removeItem('Token');
        window.localStorage.removeItem('username');
        $rootScope.logged = false;

        if (reload) {
            window.location.reload();
        }
    };

    $rootScope.$watch('logged', function (value) {
        if (angular.isDefined(value)) {
            window.sessionStorage['isLogged'] = value;
        } else {
            window.sessionStorage['isLogged'] = false;
        }
    });

    return self;
}]);