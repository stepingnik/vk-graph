
function startGraph2() {
//popover data
    var popOverSettings = {//ModalData
        placement: 'right',
        container: 'body',
        html: true,
        template: '<div class="popover"><div class="arrow" ></div><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 class="popover-title"></h3><div class="popover-content"></div>',
        title : function() {
            return $('#popover_title').html();
        },
        selector: '[data-toggle="popover"]',
        content:  function() {
            return $('#popover_content_wrapper').html();
        }
    };


//RandomColor
    function getRandomColor() {//RandomColor
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++ ) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

//Constants for the SVG
    var width = 500,
        height = 500;

//Set up the colour scale
    var color = d3.scale.category20();

//Set up the force layout
    var force = d3.layout.force()
        .charge(-120)
        .linkDistance(30)
        .size([width, height]);
//LINKDISTANCE
    force.linkDistance(function(link) {
        return link.connection === "week" ? height/10 : height/6;
    });

//Append a SVG to the body of the html page. Assign this SVG as an object to svg
    var svg = d3.select("body").append("svg")
        .attr("width", width)
        .attr("height", height).call(d3.behavior.zoom().on("zoom", function () {
            svg.attr("transform", "translate(" + d3.event.translate + ")" + " scale(" + d3.event.scale + ")")
        })).append("g");

//---Insert------
//Set up tooltip
    var tip = d3.tip()
        .attr('class', 'd3-tip')
        .offset([-10, 0])
        .html(function (d) {
            return d.name + "</span>";
        })
    svg.call(tip);
//---End Insert---


//Read the data from the mis element
    d3.json("/second/data_.json", function (error, graph) {

//close popover and change color
        $(document).on("click", ".close" , function(){
            $(this).parents(".popover").popover('hide');
            node.style("fill", function (d) {
                if(d.group==3)
                    return "blue";
                else if(d.group ==1)
                    return "red";
                else
                    return "lightblue";
            })
        });

//Creates the graph data structure out of the json data
        force.nodes(graph.nodes)
            .links(graph.links)
            .start();

//Create all the line svgs but without locations yet
        var link = svg.selectAll(".link")
            .data(graph.links)
            .enter().append("line")
            .attr("class", "link")
            .style("stroke-width", function (d) {
                return Math.sqrt(d.value);
            });
        var groups;
        var check;
//Do the same with the circles for the nodes - no
        var node = svg.selectAll(".node")
            .data(graph.nodes)
            .enter().append("circle")
            .attr("class", "node")
            .attr("r", 5)
            .style("fill", function (d) {
                if(d.group==3)
                    return "blue";
                else if(d.group ==1)
                    return "red";
                else
                    return "lightblue";
            })
            //.call(force.drag)
            .on('mouseover', function (d) {
                tip.show(d);
                groups = d.group;
                d3.select(this).style("stroke","red");
            }) //Added
            .on('mouseout',function (d) {
                tip.hide(d);
                d3.select(this).style("stroke","none");
            }); //Added


//Node on click events
        node.on("click", function(d) {
            if(groups != 1)
                $("#check").hide();
            if(groups == 1)
                $("#check").show();
            var rColor = getRandomColor();
            d3.select(this).attr('state', 0);
            //remove popovers and return colors
            node.style("fill", function (d) {
                if(d.group==3)
                    return "blue";
                else if(d.group ==1)
                    return "red";
                else
                    return "lightblue";
            })
            $('.popover').remove();

            //var img = new Image();
            d3.select(this).append("img")
                .text(function(d){$(".content").html(
                    "<img class='img-circle' src='" + d.img + "' style='width:50px;height:50px' >"
                );});//PHOTO

            d3.select(this).append("text")
                .text(function(d){$(".value").html(d.name);});//NAME

            d3.select(this).append("text")
                .text(function(d){$(".rating").html(d.rating);});//RATING

            if(groups==3)
                d3.select(this).append("text")
                    .text(function(d){$(".connection").html("Сильная");});
            else
                d3.select(this).append("text")
                    .text(function(d){$(".connection").html("Слабая");});



            if(d3.select(this).attr('state') == 1){//show-hide popover
                d3.select(this).attr('state', 0);
                //d3.select(this).style("fill", color(d.group));
                d3.select(this).style("fill", function (d) {
                    if(d.group==3)
                        return "blue";
                    else if(d.group ==1)
                        return "red";
                    else
                        return "lightblue";
                })
                $(this).popover(popOverSettings).popover("hide");
            }
            else{
                d3.select(this).attr('state', 1);
                d3.select(this).style("fill", "lime");
                $(this).popover(popOverSettings).popover("show");
            }
        });

        var nodelabels = svg.selectAll(".nodelabel")//multiNodes
            .data(graph.nodes)
            .enter()
            .append("text")
            .style("fill", "none")
            .style("font-size", "5px")
            .text(function (d) {
                return d.name;
            });

//Now we are giving the SVGs co-ordinates - the force layout is generating the co-ordinates which this code is using to update the attributes of the SVG elements
        force.on("tick", function () {
            link.attr("x1", function (d) {
                return d.source.x;
            })
                .attr("y1", function (d) {
                    return d.source.y;
                })
                .attr("x2", function (d) {
                    return d.target.x;
                })
                .attr("y2", function (d) {
                    return d.target.y;
                });

            node.attr("cx", function (d) {
                return d.x;
            })
                .attr("cy", function (d) {
                    return d.y;
                });

        });
    });
}


