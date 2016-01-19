/**
 * Created by waldek on 19.01.16.
 */

angular.module('services.messages', []).service('MessagesSvc', ['$http', '$q', 'url', function ($http, $q, url) {
    var self = {};

    self.getMessages = function(filters) {
        var defer = $q.defer();

        $http.get(url + 'messages/', {params: angular.extend({}, filters)}).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.addMessage = function (message) {
        var defer = $q.defer();

        $http.post(url + 'messages/', {data: [message]}).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.editMessage = function (message) {
        var defer = $q.defer();

        $http.put(url + 'messages/', {data: [message]}).then(function (res) {
            if (res.data.status) {
                defer.resolve(res.data.results);
            } else {
                defer.resolve([]);
            }
        });

        return defer.promise;
    };

    self.deleteMessage = function (id) {
        var defer = $q.defer();

        $http.put(url + 'messages/', {data: [id]}).then(function (res) {
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