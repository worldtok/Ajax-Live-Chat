function determine(num) {
    return num > 1 ? 's' : '';
}
const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat'];
const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

function longTime(timestamp) {
    let now = new Date().getTime();
    let dt = new Date(timestamp).getTime();
    let diff = (now - dt) / 1000;
    //  return from here
    let secsAgo = Math.abs(Math.floor(diff));
    let minsAgo = Math.abs(Math.floor(secsAgo / 60));
    let hrsAgo = Math.abs(Math.floor(minsAgo / 60));
    let daysAgo = Math.abs(Math.floor(hrsAgo / 24));
    let wksAgo = Math.abs(Math.floor(daysAgo / 7));
    let mnthsAgo = Math.abs(Math.floor(wksAgo / 4));
    let yrsAgo = Math.abs(Math.floor(mnthsAgo / 12));
    if (secsAgo < 60) {

        return `${secsAgo} seconds ago`;

    } else if (minsAgo < 60) {

        return `${minsAgo} minute${determine(minsAgo)} ago`;

    } else if (hrsAgo < 24) {

        return `${hrsAgo} hour${determine(hrsAgo)} ago`;

    } else if (daysAgo < 7) {

        return `${daysAgo} day${determine(daysAgo)} ago`;

    } else if (wksAgo < 4) {

        return `${wksAgo} week${determine(wksAgo)} ago `;

    } else {

        return `${months[new Date(timestamp).getMonth()]}
            ${new Date(timestamp).getDate()},
            ${new Date(timestamp).getFullYear()}
            at ${new Date(timestamp).getHours()}:${new Date(timestamp).getMinutes()}`;

    }
}

function shortTime(timestamp) {
    let now = new Date().getTime();
    let dt = new Date(timestamp).getTime();
    let diff = (now - dt) / 1000;
    //  return from here
    let secsAgo = Math.abs(Math.floor(diff));
    let minsAgo = Math.abs(Math.floor(secsAgo / 60));
    let hrsAgo = Math.abs(Math.floor(minsAgo / 60));
    let daysAgo = Math.abs(Math.floor(hrsAgo / 24));
    let wksAgo = Math.abs(Math.floor(daysAgo / 7));
    let mnthsAgo = Math.abs(Math.floor(wksAgo / 4));
    let yrsAgo = Math.abs(Math.floor(mnthsAgo / 12));
    if (secsAgo < 60) {

        return `${secsAgo} secs ago`;

    } else if (minsAgo < 60) {

        return `${minsAgo} min${determine(minsAgo)}`;

    } else if (hrsAgo < 24) {

        return `${hrsAgo} hr${determine(hrsAgo)}`;

    } else if (daysAgo < 7) {

        return `on ${days[new Date(diff).getDay()]}`;

    } else if (wksAgo < 4) {

        return `${wksAgo} wk${determine(wksAgo)}`;

    } else if (mnthsAgo < 12) {

        return `${mnthsAgo} mon${determine(mnthsAgo)}`;

    } else if (!isNaN(yrsAgo)) {
        return `${yrsAgo} yr${determine(yrsAgo)}`;
    } else {
        return '0:00';
    }
}