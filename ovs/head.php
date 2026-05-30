<div class="top_date" style="right: 20px; margin-left: 600px;"> 
  <font color="white">
    Date: <script>document.write(getCalendarDate());</script><br>
    Time: <span id="live-time"></span><br>
    Academic Year: <?php
      include('dbcon.php');
      $year_q = mysqli_query($conn, "SELECT academic_year FROM settings WHERE is_current = 1");
      echo mysqli_fetch_assoc($year_q)['academic_year'];
    ?>
  </font>
</div>
<script type="text/javascript">
function updateClock() {
  const now = new Date();
  const hours = now.getHours();
  const minutes = ("0" + now.getMinutes()).slice(-2);
  const seconds = ("0" + now.getSeconds()).slice(-2);
  const ampm = hours >= 12 ? "P.M." : "A.M.";
  const hourFormatted = hours % 12 || 12;
  const timeString = hourFormatted + ":" + minutes + ":" + seconds + " " + ampm;
  document.getElementById('live-time').textContent = timeString;
}
setInterval(updateClock, 1000);
updateClock();
</script>
