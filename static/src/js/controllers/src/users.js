angular.module('controllers.users', []).controller('UsersCtrl', ['$scope', 'UsersSvc', 'RoutesSvc', function ($scope, UsersSvc, RoutesSvc) {
    $scope.user = {};
    $scope.routes = [];

    UsersSvc.getUsers({_me: true}).then(function (users) {
        if (users.length > 0) {
            $scope.user = users[0];
        }
    });

    RoutesSvc.getUserRoutes({_limit: 5, _order: false}).then(function (routes) {
        $scope.routes = routes;
    });
}]);

angular.module('controllers.users').controller('UsersEditCtrl', ['$scope', 'UsersSvc', '$location', function ($scope, UsersSvc, $location) {
    $scope.user = {};

    UsersSvc.getUsers({id: $scope.$parent.ID, _auth: true}).then(function (users) {
        if (users.length > 0) {
            $scope.user = users[0];
        }
    });

    $scope.submit = function () {
        UsersSvc.editUser($scope.user).then(function () {
            $location.path('/users/');
        });
    }
}]);
