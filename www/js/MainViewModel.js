function MainViewModel() {
    var self = this;

    // private functions
    var filterList = function filterList(value) {
        $.ajax({
            url: "api/steps/query",
            data: { term: value}
        }).done( function(retrievedSteps) {
            self.steps(retrievedSteps);
        });
    };

    // Editable data
    self.filterTerm = ko.observable("");
    self.filterTerm.subscribe(function(newValue) {
        filterList(newValue);
    });
    self.steps = ko.observableArray([]);

    // Computed Data
    self.formattedSteps = ko.computed(function() {
        var formattedSteps = [];
        jQuery.each(self.steps, function(index, stepData) {
            formattedSteps.push({
                step:   stepData.step,
                method: stepData.method + "()"
            })
        });
        return formattedSteps;
    });

    filterList("");
}
ko.applyBindings(new MainViewModel());