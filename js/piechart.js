function init()

{

var w = 400;
var h = 400;
var r = h / 2;
var color = d3.scale.category20c();

d3.json("json/superman.json", function(error, data) {

var vis = d3.select('#chart').append("svg").data([data]).attr("width", w).attr("height", h).append("g").attr("transform", "translate(" + r + "," + r + ")");
var pie = d3.layout.pie().value(function(d) {
    return d.featques;
});

// declare an arc generator function
var arc = d3.svg.arc().outerRadius(r);

// select paths, use arc generator to draw
var arcs = vis.selectAll("g.slice").data(pie).enter().append("g").attr("class", "slice");
arcs.append("path")
    .attr("fill", function(d, i) {
        return color(i);
    })
    .attr("d", function(d) {
        
        return arc(d);
    });

// add the text
arcs.append("svg:text").attr("transform", function(d) {
    d.innerRadius = 0;
    d.outerRadius = r;
    return "translate(" + arc.centroid(d) + ")";
}).attr("text-anchor", "middle").text(function(d, i) {
    return (data[i].language+"("+data[i].featques+")");
});

});

}
