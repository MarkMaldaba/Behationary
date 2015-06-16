function MainViewModel() {
    var self = this;

    // private functions
    var filterList = function filterList(value, selectedProject) {
        $.ajax({
            url: "api/" + selectedProject + "/steps/query",
            data: { term: value}
        }).done( function(retrievedSteps) {
            self.steps(retrievedSteps);
        });
    };

    var highlightFilterTerm = function highlightFilterTerm(inString) {
        inString = escapeHtml(inString);
        var searchTerm = self.filterTerm();
        if (searchTerm !== "") {
            // Wrap in a span with styling.
            inString = inString.split(searchTerm)
                               .join('<span class="highlightedSearchTerm">' + searchTerm + '</span>')
        }
        return inString;
    };

    // Editable data
    self.filterTerm = ko.observable("");
	self.selectedProject = ko.observable($("#SelectedProject").val());

    self.filterTerm.subscribe(function(newValue) {
        filterList(newValue, self.selectedProject());
    });
    self.selectedProject.subscribe(function(newValue) {
        filterList(self.filterTerm(), newValue);
    });

    self.steps = ko.observableArray([]);

    // Computed Data
    self.formattedSteps = ko.computed(function() {
        var formattedSteps = [];
        jQuery.each(self.steps(), function(index, stepData) {
            var searchTerm = self.filterTerm();
            formattedSteps.push({
                step:       highlightFilterTerm(stepData.step),
                method:     stepData.method.fullVariableName + "()",
                lineNumber: stepData.method.lineNumber
            })
        });
        return formattedSteps;
    });

    filterList(self.filterTerm(), self.selectedProject());
}
ko.applyBindings(new MainViewModel());

// HTML escaper taken from mustache.js
var entityMap = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': '&quot;',
    "'": '&#39;',
    "/": '&#x2F;'
};

function escapeHtml(string) {
    return String(string).replace(/[&<>"'\/]/g, function (s) {
      return entityMap[s];
    });
}