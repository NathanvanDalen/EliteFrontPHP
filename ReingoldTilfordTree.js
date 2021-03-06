/* global d3, self */

function showTree(url,spinner) {

//            var margin = {top: 20, right: 120, bottom: 20, left: 120},
//            width = 960 - margin.right - margin.left,
//                    height = 800 - margin.top - margin.bottom;
    
    var i = 0,
            duration = 750,
            root,
            realWidth = window.innerWidth,
            realHeight = window.innerHeight,
            m = [40, 240, 40, 240],
            w = realWidth - m[0] - m[0],
            h = realHeight - m[0] - m[2];
//            var tree = d3.layout.tree()
//                    .size([height, width]);

//            var diagonal = d3.svg.diagonal()
//                    .projection(function (d) {
//                        return [d.y, d.x];
//                    });

//            var svg = d3.select("body").append("svg")
//                    .attr("width", width + margin.right + margin.left)
//                    .attr("height", height + margin.top + margin.bottom)
//                    .append("g")
//                    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    //var d3;
    var diameter = 1000;
    var barHeight = 5;
    var barWidth = 20;
    var size = function (d) {
        if (d.size != null) {

            if (d.size < 10.264) {
                return 3;
            }
            if (10.264 > d.size < 100) {
                return Math.sqrt(((d.size ^ 2) / 2) / Math.PI);
            }
        }

        else {
            return 6;
        }

        ;
    };
    var tree = d3.layout.tree()
            .size([360, diameter / 2 - 120])
            .separation(function (a, b) {
                return (a.parent == b.parent ? 1 : 1) / a.depth + 500;
            });

    var diagonal = d3.svg.diagonal.radial()
            .projection(function (d) {
                return [d.y, d.x / 180 * Math.PI];
            });

    var svg = d3.select("div.treeContainer").append("svg")
            .attr("width", diameter)
            .attr("height", diameter - 150)
            .append("g")
            .attr("transform", "translate(" + diameter / 2 + "," + diameter / 2 + ")");
    d3.select("svg").call(d3.behavior.zoom()
            .scaleExtent([0.5, 5])
            .on("zoom", zoom));
    d3.json(url, function (error, response) {
        if (error)
        {
            throw error;
        }
        spinner.stop();
        root = response;
        root.x0 = diameter / 2;
        root.y0 = diameter / 2;

        function collapse(d) {
            if (d.children) {
                d._children = d.children;
                d._children.forEach(collapse);
                d.children = null;
            }
        }

        root.children.forEach(collapse);
        update(root);
    });
    function zoom() {
        var scale = d3.event.scale,
                translation = d3.event.translate,
                tbound = -h * scale,
                bbound = h * scale,
                lbound = (-w + m[1]) * scale,
                rbound = (w - m[3]) * scale;
        // limit translation to thresholds
        translation = [
            Math.max(Math.min(translation[0], rbound), lbound),
            Math.max(Math.min(translation[1], bbound), tbound)
        ];
        d3.select(".drawarea")
                .attr("transform", "translate(" + translation + ")" +
                        " scale(" + scale + ")");
    }

    d3.select(self.frameElement).style("height", "800px");

    function update(source) {

        // Compute the new tree layout.
        var nodes = tree.nodes(root).reverse(),
                links = tree.links(nodes);

        // Normalize for fixed-depth.
        nodes.forEach(function (d) {
            d.y = d.depth * 180;
        });

        // Update the nodes…
        var node = svg.selectAll("g.node")
                .data(nodes, function (d) {
                    return d.id || (d.id = ++i);
                });

        // Enter any new nodes at the parent's previous position.
        var nodeEnter = node.enter().append("g")
                .attr("class", "node")
                .attr("transform", function (d) {
                    return "translate(" + source.y0 + "," + source.x0 + ")";
                })
                .on("click", click);

        nodeEnter.append("circle")
                .attr("r", 1e-6)
                .style("fill", function (d) {
                    return d._children ? "lightsteelblue" : "#fff";
                });
// adding Href property to text
        nodeEnter.append("a")
                .attr("xlink:href", function (d) {
                    return d.url;
                })  // <-- reading the new "url" property
                .append("text").attr("x", function (d) {
            return d.children || d._children ? -10 : 10;
        })
                .attr("dy", ".35em")
                .attr("text-anchor", function (d) {
                    return d.x < 180 ? "start" : "end";
                })
                .attr("transform", function (d) {
                    return d.x < 180 ? "translate(" + d.size / 80 + ")" : "rotate(180)translate(-" + d.size / 80 + ")";
                })
                .text(function (d) {
                    return d.name;
                })
                .style("fill-opacity", 1e-6);


        // Transition nodes to their new position.
        var nodeUpdate = node.transition()
                .duration(duration)
                .attr("transform", function (d) {
                    return "rotate(" + (d.x - 90) + ")translate(" + (d.y) + ")";
                });

        nodeUpdate.select("circle")
                .attr("r", size)
                .style("fill", function (d) {
                    return d._children ? "lightsteelblue" : "#fff";
                });

        nodeUpdate.select("text")
                .style("fill-opacity", 1);

        // Transition exiting nodes to the parent's new position.
        var nodeExit = node.exit().transition()
                .duration(duration)
                .attr("transform", function (d) {
                    return "rotate(" + (d.x - 90) + ")translate(" + d.y + ")";
                })
                .remove();

        nodeExit.select("circle")
                .attr("r", 1e-6);

        nodeExit.select("text")
                .style("fill-opacity", 1e-6);

        // Update the links…
        var link = svg.selectAll("path.link")
                .data(links, function (d) {
                    return d.target.id;
                });

        // Enter any new links at the parent's previous position.
        link.enter().insert("path", "g")
                .attr("class", "link")
                .attr("d", function (d) {
                    var o = {x: source.x0, y: source.y0};
                    return diagonal({source: o, target: o});
                });

        // Transition links to their new position.
        link.transition()
                .duration(duration)
                .attr("d", diagonal);

        // Transition exiting nodes to the parent's new position.
        link.exit().transition()
                .duration(duration)
                .attr("d", function (d) {
                    var o = {x: source.x, y: source.y};
                    return diagonal({source: o, target: o});
                })
                .remove();

        // Stash the old positions for transition.
        nodes.forEach(function (d) {
            d.x0 = d.x;
            d.y0 = d.y;
        });
    }

    // Toggle children on click.
    function click(d) {
        if (d.children) {
            d._children = d.children;
            d.children = null;
        } else {
            d.children = d._children;
            d._children = null;
        }
        update(d);
    }}
