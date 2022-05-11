var tribute = null;

$(document).ready(function () {
    function remoteSearch(query, callback) {
        $.getJSON(U_AJAX_MENTION_URL, {q: query}, function (data) {
            callback(data)
        });
    }

    tribute = new Tribute({
        collection: [{
            trigger: '@',
            menuItemTemplate: function (item) {
                return item.string;
            },

            selectTemplate: function (item) {
                return '[mention]' + item.original.value + '[/mention]';
            },

            values: function (text, cb) {
                remoteSearch(text, cb);
            },
            spaceSelectsMatch: true,
        }]
    });
    tribute.attach($('[name="message"]'));
});
