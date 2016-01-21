/**
 * Created by waldek on 09.01.16.
 */

angular.module('services.routes', []).service('RoutesSvc', ['$http', '$q', 'url', function ($http, $q, url) {
    var self = {};

    self.getRoutes = function(filters) {
        var defer = $q.defer();

        $http.get(url + 'routes/', {params: angular.extend({}, filters)}).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.getUserRoutes = function (filters) {
        var defer = $q.defer();

        $http.get(url + 'users/routes/', {params: angular.extend({}, filters)}).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.deleteUserRoute = function (id) {
        var defer = $q.defer();

        $http.delete(url + 'users/routes/', {
            params: {
                data: [{ID: id}]
            }
        }).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.addRoute = function (route) {
        var defer = $q.defer();

        $http.post(url + 'routes/', {data: [route]}).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.editRoute = function (route) {
        var defer = $q.defer();

        $http.put(url + 'routes/', {data: [route]}).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.editUserRoute = function (route) {
        var defer = $q.defer();

        $http.put(url + '/users/routes/', {data: [route]}).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.deleteRoute = function (id) {
        var defer = $q.defer();

        $http.delete(url + 'routes/', {
            params: {
                data: [{ID: id}]
            }
        }).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.addRouteComment = function (data) {
        var defer = $q.defer();

        $http.post(url + 'routes/comments/', {data: [data]}).then(function (res) {
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