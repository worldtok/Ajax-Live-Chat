function showsmiley(input) {
    var k, len, split_on_unicode, text, val;
    if (!input) {
        return '';
    }
    if (!Config.rx_codes) {
        Config.init_unified();
    }
    split_on_unicode = input.split(Config.rx_codes);
    for (k = 0, len = split_on_unicode.length; k < len; k++) {
        text = split_on_unicode[k];
        val = '';
        if (Config.rx_codes.test(text)) {
            val = Config.reversemap[text];
            if (val) {
                val = ':' + val + ':';
                val = $.emojiarea.createIcon($.emojiarea.icons[val]);
            }
        } else {
            // val = document.createTextNode(text);
        }
        // element.append(val);
    }
    return input.replace(Config.rx_codes, function (m) {
        var $img;
        val = Config.reversemap[m];
        if (val) {
            val = ':' + val + ':';
            $img = $.emojiarea.createIcon($.emojiarea.icons[val]);
            return $img;
        } else {
            return '';
        }
    });
};

// and this is how to call it
showsmiley(yourunicodeinput);