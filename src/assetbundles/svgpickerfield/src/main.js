const initSvgPicker = (context) => {
    let {id, name, namespace, prefix, svgs, value} = context;
    let $select = $(`#${namespace}`).selectize({
        valueField:'id',
        labelField:'id',
        searchField:'id',
        options: svgs,
        render:{
            option: function(data,escape) {
                return `<div style="display:flex;align-items:center;"><svg style="height:24px" viewBox="${data.viewBox}">${data.symbol}</svg><span style="padding-left:10px;">${data.id}</span></div>`;
            },
            item: function(data,escape) {
                return `<div style="display:flex;align-items:center;"><svg style="height:24px" viewBox="${data.viewBox}">${data.symbol}</svg><span style="padding-left:10px;">${data.id}</span></div>`;
            }
        }
    })[0].selectize;
    $select.setValue(value);
}

window.initSvgPicker = initSvgPicker;