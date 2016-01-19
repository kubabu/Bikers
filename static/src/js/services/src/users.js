/**
 * Created by waldek on 19.01.16.
 */

angular.module('services.users', []).service('UsersSvc', ['$http', '$q', 'url', function ($http, $q, url) {
    var self = {};

    self.getUsers = function(filters) {
        var defer = $q.defer();

        $http.get(url + 'users/', {params: angular.extend({}, filters)}).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.addUser = function (user) {
        var defer = $q.defer();

        $http.post(url + 'users/', {data: [user]}).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.editUser = function (user) {
        var defer = $q.defer();

        $http.put(url + 'users/', {data: [user]}).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.deleteUser = function (id) {
        var defer = $q.defer();

        $http.put(url + 'users/', {data: [id]}).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    return self;
}]);