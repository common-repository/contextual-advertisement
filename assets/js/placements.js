(function (Requests, window, document, $) {
    /**
     * Get (checked in form) placement positions (Post, Frontpage, etc.)
     * @return {Array}
     */
    function getCheckedPlacementPositions() {
        var elements = $('[name=formPlacementsDisplay]');
        var placements = [];
        for (var i = 0; i < elements.length; i++) {
            if (elements[i].checked) {
                placements.push(elements[i].value);
            }
        }
        return placements;
    }

    /**
     * Get (checked in form) placements e.g. `Intext Placement 123`
     * @return {Array}
     */
    function getCheckedPlacements() {
        var elements = $('[data-name=formPlacementsList]');
        var placements = [];
        for (var i = 0; i < elements.length; i++) {
            if (elements[i].checked) {
                placements.push(elements[i].value);
            }
        }
        return placements;
    }

    var form = $('#check-list');
    var placementsIdForm = $('#placement-list');

    form.on('submit', function (e) {
        e.preventDefault();

        var request = Requests();

        var placementsData = {
            placementDisplay: getCheckedPlacementPositions(),
            action: 'context360_placements_position'
        };
        request.onSuccess(function (code, responseText) {
            var responseObject = JSON.parse(responseText);
            if (responseObject.success) {
                window.location.reload();
            }
        });
        request.post(ajaxurl, {}, placementsData);
    });

    placementsIdForm.on('submit', function (e) {
        e.preventDefault();

        var request = Requests();

        var placementsData = {
            placementDisplay: getCheckedPlacements(),
            action: 'context360_placements_list'
        };
        request.onSuccess(function () {
            window.location.reload();
        });
        request.post(ajaxurl, {}, placementsData);
    });

})(Context360RequestFactory, window, document, jQuery);