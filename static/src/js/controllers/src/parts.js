angular.module('controllers.parts', []).controller('PartsNewCtrl', ['$scope', '$location', 'PartsSvc', function ($scope, $location, PartsSvc) {
    $scope.part = {
        _bike_ID: $scope.$parent.ID
    };

    $scope.submit = function () {
        PartsSvc.addPart($scope.part).then(function (res) {
            if(res.length > 0){
                $location.path('/bikes/show/' + $scope.part._bike_ID);
            } else {
                $location.path('/bikes/');
            }
        });
    };
}]);

angular.module('controllers.parts').controller('PartsCtrl', ['$scope', 'PartsSvc', function ($scope, PartsSvc) {
    $scope.parts = [];

    PartsSvc.getParts().then(function (parts) {
        $scope.parts = parts;
    });
}]);
