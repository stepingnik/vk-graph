var t = 0;
function startGraph() {
    Math.seedrandom('mySeed');
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
    var width = screen.width;
    height = screen.height - 300;

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

    var scaleFactor = 1;
    var translation = [0,0];
    var node;
    var link;
    var zoomer = d3.behavior.zoom()
        .scaleExtent([0.5,7])
        //allow 10 times zoom in or out
        .on("zoom", zoom);
    //define the event handler function
    function zoom() {
        //console.log("zoom", d3.event.translate, d3.event.scale);
        scaleFactor = d3.event.scale;
        translation = d3.event.translate;
        tick(); //update positions
    }




//Append a SVG to the body of the html page. Assign this SVG as an object to svg
    var svg = d3.select("body").append("svg")
        .attr("width", width)
        .attr("height", height)
        //.call(d3.behavior.zoom().scaleExtent([0.5,7]).on("zoom", function () {
        //    svg.attr("transform", "translate(" + d3.event.translate + ")" + " scale(" + d3.event.scale + ")")
        //}))
        .append("g").call(zoomer);



    var rect = svg.append("rect")
        .attr("width", width)
        .attr("height", width)
        .style("fill", "none")
        //make transparent (vs black if commented-out)
        .style("pointer-events", "all");


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
    if(t == 0){
    //var json_name ="/second/"+$('#json_name').val(); БЕЗ БД
    var json_name ="data_12.json";
//Read the data from the mis element


    t=1;
}
else{
    var json_name ="data_22.json";
    t=0;
}

d3.json(json_name, function (error, graph) {

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

        //force.stop();
        for (var i = 0; i < 400; ++i) force.tick();//hidden drawing

//Create all the line svgs but without locations yet
        link = svg.selectAll(".link")
            .data(graph.links)
            .enter().append("line")
            .attr("class", "link")
            .attr("x1", function(d) { return d.source.x; })//моментальная отрисовка
            .attr("y1", function(d) { return d.source.y; })
            .attr("x2", function(d) { return d.target.x; })
            .attr("y2", function(d) { return d.target.y; })
        //.style("stroke-width", function (d) {
        //    return Math.sqrt(d.value);
        //});
        var groups;
        var check;
//Do the same with the circles for the nodes - no
        node = svg.selectAll(".node")
            .data(graph.nodes)
            .enter().append("circle")
            .attr("class", "node")
            .attr("r", 5).attr("cx", function(d) { return d.x; }).attr("cy", function(d) { return d.y; })//моментальная отрисовка
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
        //force.on("tick", tick); мнгновенная отрисовка


    });
    function tick() {
        link.attr("x1", function (d) {
            return translation[0] + scaleFactor*d.source.x;
        })
            .attr("y1", function (d) {
                return translation[1] + scaleFactor*d.source.y;
            })
            .attr("x2", function (d) {
                return translation[0] + scaleFactor*d.target.x;
            })
            .attr("y2", function (d) {
                return translation[1] + scaleFactor*d.target.y;
            });

        node.attr("cx", function (d) {
            return translation[0] + scaleFactor*d.x;
        })
            .attr("cy", function (d) {
                return translation[1] + scaleFactor*d.y;
            });

    }
    setTimeout(function()
    {
        console.log("qqq");
        d3.select("svg").remove();
        d3.select(".d3-tip").remove();
        startGraph();

    }, 1000);

}


