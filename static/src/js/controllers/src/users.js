angular.module('controllers.users', []).controller('UsersCtrl', ['$scope', function ($scope) {
    $scope.user = {
        bikes: [{name:"Romet Osa", description:"Składak z koralikami na kołach"}],
        routes: [{name:"do sklepu", bike:{name:"Romet"},
                 date_of_ride:"2015-12-04 16:45", duration_of_ride:12
        }]
    };
}]);

angular.module('controllers.users').controller('UsersNewCtrl', ['$scope', function ($scope) {
    $scope.user = {};
    $scope.submit = function () {

    }
}]);
