// variables
Time = document.getElementById('time-t');
Day = document.getElementById('day-t');
Month = document.getElementById('month-t');
Year = document.getElementById('year-t');
condition = document.getElementById('condition');

// to change html text
toChangeDay = document.getElementById('day-c');
toChangeHour = document.getElementById('hours-c');
toChangeMin = document.getElementById('min-c');

const countdown = () =>{
    // declarations
    var getInnerTime = Time.innerHTML;
    var getInnerDate = Day.innerHTML;
    var getInnerMonth = Month.innerHTML;
    var getInnerYear = Year.innerHTML;

    // dates now and then    
    const countDate = new Date(getInnerMonth+" "+getInnerDate+","+getInnerYear+" "+getInnerTime+":00" ).getTime();
    const now = new Date().getTime();

    if(now > countDate){
        const gap = now - countDate;
        // time logics
        const seconds = 1000;
        const minutes = seconds * 60;
        const hours = minutes * 60;
        const days = hours * 24;

        // calculate millis > text
        const textDay = Math.floor(gap / days);
        const textHour = Math.floor((gap % days)/ hours);
        
        toChangeDay.innerHTML = textDay;
        toChangeHour.innerHTML = textHour;
        condition.innerHTML = 'geleden';
    }else{
        const gap = countDate - now;

        // time logics
        const seconds = 1000;
        const minutes = seconds * 60;
        const hours = minutes * 60;
        const days = hours * 24;

        // calculate millis > text
        const textDay = Math.floor(gap / days);
        const textHour = Math.floor((gap % days)/ hours);
        const textMin = Math.floor((gap % hours) / minutes);
        
        toChangeDay.innerHTML = textDay;
        toChangeHour.innerHTML = textHour;
        if(textDay == 0 && textHour <= 1){
            toChangeMin.innerHTML = textMin+'m';
        }else{
            toChangeMin.innerHTML = '';
        }
        condition.innerHTML = 'resterend';
    }
}
countdown();
setInterval(countdown, 60000);