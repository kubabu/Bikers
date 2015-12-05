angular.module('services.bikes', []).service('BikesSvc', ['$http', '$q', 'url', function ($http, $q, url) {
    var self = {};

    self.getBikes = function(filters) {
        var defer = $q.defer();

        $http.get(url + 'bikes/', angular.extend({}, filters)).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.addBike = function (bike) {
        var defer = $q.defer();

        $http.post(url + 'bikes/', {data: [bike]}).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.editBike = function (bike) {
        var defer = $q.defer();

        $http.put(url + 'bikes/', {data: [bike]}).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.deleteBike = function (id) {
        var defer = $q.defer();

        $http.put(url + 'bikes/', {data: [id]}).then(function (res) {
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
