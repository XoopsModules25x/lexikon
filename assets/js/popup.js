function popup(url, name, width, height) {
    settings = "toolbar=no,location=no,directories=no," + "status=no,menubar=no,scrollbars=yes," + "resizable=yes,width=" + width + ",height=" + height;
    MyNewWindow = window.open(url, name, settings);
}
