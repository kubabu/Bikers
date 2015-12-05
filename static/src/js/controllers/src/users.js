angular.module('controllers.users', []).controller('UsersCtrl', ['$scope', function ($scope) {
    $scope.user = {};
}]);

angular.module('controllers.users').controller('UsersNewCtrl', ['$scope', function ($scope) {
    $scope.user = {};
    $scope.submit = function () {

    }
}]);