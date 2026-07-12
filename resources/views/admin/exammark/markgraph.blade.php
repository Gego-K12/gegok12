@extends('layouts.admin.layout')

@section('content')

<div class="">
        <div class="flex items-center justify-between">
            <h1 class="admin-h1 my-3 flex items-center">
                <a href="{{ url('/admin/student/show/'.$user->name) }}" title="Back" class="rounded-full bg-gray-100 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124 c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844 L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412 c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008    c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
                </a>
                <span class="mx-3">Marks Graph</span>
            </h1>
        </div>
       <div id="chart_div" style="width: 900px; height: 500px;"></div>
    </div>
    

 @endsection   
 @push('scripts')
 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable(
          <?php echo json_encode($subjects); ?>,
         /* ['2004/05',  50,      70,         80,             0,           45,      45.6],
          ['2005/06',  70,      52,        80,             85,          45,      45],
          ['2006/07',  70,      80,        52,             52,           52,      45],
          ['2007/08',  70,      80,        85,             45,           45,      45.4],
          ['2008/09',  70,      80,         85,             45,          45,      45.6]*/
        );

        var options = {
          title : 'Mark Comparison',
          vAxis: {title: 'Marks'},
          hAxis: {title: 'Exams'},
          seriesType: 'bars',
          series: {5: {type: 'line'}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
 @endpush