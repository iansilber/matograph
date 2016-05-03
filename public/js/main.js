var TMDB_API = "6d321384e1ed1b36a83ab7160b71f130";
var ROTTEN_TOMATOES_API = "wcaad6vkbjb5hjwzhjwf7jw3";

function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function get_movie_list(actor_id) {
  $.ajax({
      url: "https://api.themoviedb.org/3/person/" + actor_id + "/movie_credits?api_key=" + TMDB_API,
      dataType: 'jsonp',
      cache: true,
      success: function(data) {
        for (var i = 0; i < data.cast.length; i++) {
          console.log(data.cast[i].title);
        }
        find_movies_on_rotten_tomatoes(data.cast)

      },
      error: function(xhr, status, err) {
        console.error("get_movie_list", status, err.toString());
        $(".message").text("Whoops! We couldn't find that actor's movie list. Please try again.");
      }
  });

}

function find_movies_on_rotten_tomatoes(movies) {
  var rotten_tomaotes_movie_list = []
  $.ajax({
      url: "./get_movie_details",
      method: "post",
      data: {
        _token: CSRF_TOKEN,
        "movies": movies
      },
      dataType: 'json',
      cache: true,
      success: function(data) {
          display_final_results(data)    

      },
      error: function(xhr, status, err) {
        console.error("find_movies_on_rotten_tomatoes", status, err.toString());
      }
  });
}


function display_final_results(movies) {

  var simple_list = [];
  total_critic = 0;
  total_audience = 0;
  highest_scoring = {};
  worst_scoring = {};

  for (i = 0; i < movies.length; i++) {
    if (movies[i] != null) {
      if (("title" in movies[i]) && ("theater" in movies[i].release_dates) && movies[i].ratings.critics_score != -1) {
        console.log(movies[i].title);
        total_critic += movies[i].ratings.critics_score;
        total_audience += movies[i].ratings.audience_score;

        if (jQuery.isEmptyObject(highest_scoring) || movies[i].ratings.critics_score > highest_scoring.ratings.critics_score) {
          highest_scoring = movies[i];
        }

        if (jQuery.isEmptyObject(worst_scoring) || movies[i].ratings.critics_score < worst_scoring.ratings.critics_score) {
          worst_scoring = movies[i];
        }

        simple_list.push({
          date: movies[i].release_dates.theater, 
          label: movies[i].title, 
          critics_score: movies[i].ratings.critics_score,
          audience_score: movies[i].ratings.audience_score,
          link: movies[i].links.alternate,
          poster: movies[i].posters.thumbnail}
        );
      }
    }
    // datapoints.push(movies[i].ratings.critics_score);
  }

  $("#average_critic .value").html(Math.round(total_critic / simple_list.length) + "%");
  $("#average_audience .value").html(Math.round(total_audience / simple_list.length) + "%");
  
  $("#highest_scoring .value").html(highest_scoring.title + "<br /><span class='fade'>" + highest_scoring.ratings.critics_score + "% critic" + "<br />" + highest_scoring.ratings.audience_score + "% audience</span>");
  $("#highest_scoring .thumbnail").attr("src", highest_scoring.posters.thumbnail);
  $("#highest_scoring a").attr("href", highest_scoring.links.alternate);

  $("#worst_scoring .value").html(worst_scoring.title + "<br /><span class='fade'>" + worst_scoring.ratings.critics_score + "% critic" + "<br />" + worst_scoring.ratings.audience_score + "% audience</span>");
  $("#worst_scoring .thumbnail").attr("src", worst_scoring.posters.thumbnail);

  $("#worst_scoring a").attr("href", worst_scoring.links.alternate);

  simple_list = simple_list.sort(function(a,b){
    var c = new Date(a.date);
    var d = new Date(b.date);
    return c-d;
  });

  var labels = [];
  var critics_score = [];
  var audience_score = [];
  var datapoints = [];
  var posters = [];
  for (i = 0; i < simple_list.length; i++) {
    // console.log(movies[i].title, "Critic Rating: " + movies[i].ratings.critics_score)
    labels.push(simple_list[i].label);
    critics_score.push(simple_list[i].critics_score);
    audience_score.push(simple_list[i].audience_score);
    posters.push(simple_list[i].poster);
  }

  $('.stat_center').fadeIn();


  $('#chart').highcharts({
      chart: {
          type: 'spline',
          backgroundColor: 'none'
      },
      title: {
          text: ''
      },
      yAxis: {
        max: 100,
        min: 0,
        minTickInterval: 50,
          title: {
              text: '',
          },
          gridLineColor: 'none'
      },
      xAxis: {
        categories: labels,
        tickLength: 0,
        labels: {
          enabled: false
        },
        lineColor: "rgba(255,255,255,0.3)"
      },
      tooltip: {
        // useHTML: true,
        backgroundColor: "rgba(255, 255, 255, 0.95)",
        borderColor: 'none',
        borderRadius: 12,
        headerFormat: '<small>{point.key}</small><table>',
        pointFormat: '<tr><td style="color: {series.color}">{series.name}: </td>' +
                    '<td style="text-align: right"><b>{point.y} EUR</b></td></tr>',
        footerFormat: '</table>',

        formatter: function(x) {
          console.log(x);
          var index = this.points[0].point.index;
            return '<div class="tooltip_thumbnail"><img src="' + posters[index] + '" title="" alt="" border="0" height="100" width="60"></div><p><strong>' + labels[index] + '</strong><br />'+ critics_score[index] + '% critic<br />' + critics_score[index] + '% audience<br />' + moment(simple_list[index].date).format("YYYY") + '</p></div>';
        },
        shadow: false,
        shared: true,
        style: {
          padding: 20,
          fontFamily: "Helvetica Neue",
        }

      },
      credits: {
          enabled: false
      },
      legend: {
        enabled: true,
        align: "center",
        itemStyle: {
          color: "rgba(255,255,255,0.5)",
          fontWeight: "normal",
          fontFamily: "Roboto",
        },
        itemHiddenStyle: {
          color: "rgba(255,255,255,0.25)",
        },
        itemHoverStyle: {
          color: "rgba(255,255,255,1)",
        },
        symbolWidth: 0,
        symbolPadding: 10
      },
      plotOptions: {
        series: {
          marker: {
            radius: 3
          }
        }
      },
      series: [{
        lineWidth: 2,
        data: critics_score,
        name: "Critics Score",
        animation: false,
        color: "#ffffff",
        dataLabels: labels,
        cursor: "pointer",
        point: {
          events: {
              click: function(details) {
                window.open(simple_list[details.point.index].link, '_blank')
                console.log(x);
              }
          },
        },
      },
      {
        visible: false,
        data: audience_score,
        name: "Audience Score",
        animation: false,
        color: "#86C8E9",
        dataLabels: labels,
        cursor: "pointer",
        marker: {
          symbol: "circle"
        },
        point: {
          events: {
              click: function(details) {
                window.open(simple_list[details.point.index].link, '_blank')
                console.log(x);
              }
          },
        },
      }
      ]
  });
}

function set_background_photo(actor_id) {
  $.ajax({
      url: "https://api.themoviedb.org/3/person/" + actor_id + "/tagged_images?api_key=" + TMDB_API,
      
      dataType: 'jsonp',
      cache: true,
      success: function(data) {
        $(".bg").css("background-image", "linear-gradient(rgba(0, 0, 0, 0.88), rgba(0, 0, 0, 0.88)), url(http://image.tmdb.org/t/p/w500/" + data.results[Math.floor(Math.random()*data.results.length)].file_path + ")")
      },
      error: function(xhr, status, err) {
        console.error("set_background_photo", status, err.toString());
      }
  });


}

function find_actor(person) {
  $(".loader").fadeIn();
  $.ajax({
    url: "https://api.themoviedb.org/3/search/person/?api_key=" + TMDB_API + "&query=" + person,
    
    dataType: 'jsonp',
    cache: true,
    success: function(data) {
      console.log(data)
      if (data.results.length > 0) {
        var actor_id = data.results[0].id;
        get_movie_list(actor_id)
        set_background_photo(actor_id)
      } else {
        console.log("no results found!");
        $(".message").text("No results found. Please try again.");
        $(".loader").fadeOut();
      }
    },
    error: function(xhr, status, err) {
      console.error(xhr, status, err.toString());
      $(".message").text("Whoops! There was a problem! " + err.toString());
    }
  });
}


$(function() {
    $("input.person").focus(function() {
      $(this).select();
    });
    $("input.person").mouseup(function(e){
      e.preventDefault();
    });
    $("input.person").autocomplete({
        minLength: 0,
        delay:5,
        position: { my: "left top+8", at: "left bottom", collision: "none" },
        source: "./suggestions",
        focus: function( event, ui ) {
            $(this).val( ui.item.value );
            return false;
        },
        select: function( event, ui ) {
            $(this).val( ui.item.value );
            $("#person_form").submit();
            return false;
        }
    }).data("uiAutocomplete")._renderItem = function( ul, item ) {
        return $("<li></li>")
            .data( "item.autocomplete", item )
            .append( "<a>" + (item.img?"<div class='imdbImage'><img src='./imdb_poster?url=" + window.btoa(item.img) + "' /></div>":"") + "<span class='imdbTitle'>" + item.label + "</span><div class='clear'></div></a>" )
            .appendTo( ul );
    };

      $("form#person_form").submit(function() {
        window.location.href = "./details?p=" + encodeURIComponent($("input.person").val());
        return false;
    });
});

