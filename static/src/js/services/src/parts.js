angular.module('services.parts', []).service('PartsSvc', ['$http', '$q', 'url', function ($http, $q, url) {
    var self = {};

    self.getParts = function(filters) {
        var defer = $q.defer();

        $http.get(url + 'parts/', angular.extend({}, filters)).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.addPart = function (part) {
        var defer = $q.defer();

        $http.post(url + 'parts/', {data: [part]}).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.editPart = function (part) {
        var defer = $q.defer();

        $http.put(url + 'parts/', {data: [part]}).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.deletePart = function (id) {
        var defer = $q.defer();

        $http.delete(url + 'parts/', {
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

    self.deleteBikePart = function (bikePart) {
        var defer = $q.defer();

        $http.delete(url + 'bikes/parts/', {
            params: {
                data: [bikePart]
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

    return self;
}]);
