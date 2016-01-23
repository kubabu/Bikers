angular.module('controllers.messages', []).controller('MessagesCtrl', ['$scope', 'MessagesSvc', function ($scope, MessagesSvc) {
    $scope.messages = [];

    MessagesSvc.getMessages({_group: true, _order: false}).then(function (messages) {
        $scope.messages = messages;
    });
}]);

angular.module('controllers.messages').controller('MessagesNewCtrl', ['$scope', 'MessagesSvc', 'UsersSvc', '$location', function ($scope, MessagesSvc, UsersSvc, $location) {
    $scope.message = {};
    $scope.previous_messages = [];
    $scope.users = [];
    $scope.disabled = false;

    if (angular.isDefined($scope.$parent.ID)) {
        $scope.message.to_user = $scope.$parent.ID;
        $scope.disabled = true;

        MessagesSvc.getMessages({_limit: 5, _order: false, _receiver: $scope.$parent.ID}).then(function (messages) {
            $scope.previous_messages = messages;

            angular.forEach($scope.previous_messages, function (message) {
                MessagesSvc.setRead(message.ID);
            });
        });
    }

    UsersSvc.getUsers({_not_me: true}).then(function (users) {
        $scope.users = users;

        if (users.length > 0 && angular.isUndefined($scope.message.to_user)) {
            $scope.message.to_user = users[0].ID;
        }
    });

    $scope.submit = function () {
        MessagesSvc.addMessage($scope.message).then(function () {
            $location.path('/messages/');
        });
    };
}]);