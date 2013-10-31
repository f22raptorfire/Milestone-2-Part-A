package data_scraping;

import org.joda.time.DateTime;
import org.joda.time.Weeks;
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;


public class Scraper {
	    
    public void scrapeCanvas() throws Exception 
    {
    	  String url = "http://canvas.net";
          Document homePage = Jsoup.connect(url).get();

          //get course link elements
          Elements courseLinks = homePage.select(".btn.btn-action.learn-more-button");
           
          //data structures to store content
          Document coursePage; 
          
          //get content from each course page
          for (Element link : courseLinks) {
          	//get page contents
          	coursePage = Jsoup.connect(link.attr("href")).get();
          	
          	//get image and store        	
           	Element image = coursePage.select("div[class=featured-course-image] span").get(0);	
          	String attr = image.attr("style");
          	
           	Database.pushImage(url + attr.substring( attr.indexOf("/"), attr.indexOf(")")));
          
          	//get course name and store
          	Elements courseName = coursePage.select("h2[class=emboss-light]");
          	Database.pushCourseName(courseName.text()); 
          	      	
          	//start date and store
          	Element startDate = coursePage.select("div[class=course-detail-info] > p > strong").get(0);
          	String s = startDate.text();
          	
          	String r;	
          	boolean endDefined = true; 
          	if(s.matches("Self-paced, available.*"))
          	{
          		String startDateRegex = "(Self-paced, available )?(.*)";
              	r = "$2";
              	s = s.replaceAll(startDateRegex, r);
              	endDefined = false;	
          	}
          	Database.pushStartDate(s);
          	
          	//get course length
          	if(endDefined)
          	{
          		//get end date
          		Element endDate = coursePage.select("div[class=course-detail-info] > p > strong").get(1);
          		String ed = endDate.text();
          		String endDateRegex = "(.*)";
          		r = "$1";
          		//System.out.println(r);
          		s = s.replaceAll(endDateRegex, r);
          		
          		//convert from string to date type
              	SimpleDateFormat dt = new SimpleDateFormat("MMM dd, yyyy"); 
              	Date date1 = dt.parse(s); 
              	Date date2 = dt.parse(ed);
              	
              	//weeks test
              	DateTime dateTime1 = new DateTime(date1);
              	DateTime dateTime2 = new DateTime(date2);

              	s = Integer.toString(Weeks.weeksBetween(dateTime1, dateTime2).getWeeks());
          		//System.out.println(s);
          	} else {
          		s = "indefinite";
          	}
          	Database.pushCourseLength(s);
          	
          	//get professor name and store
            	Elements professor = coursePage.select("div[class=instructor-bio] h3");	
            	Database.pushProfessor(professor.text()); 
          	
          	//get instructor image links and store
          	Elements instructorImage = coursePage.select("div[class=instructor-bio] img");	
            	ArrayList<String> temp = new ArrayList<String>();
          	for(Element e : instructorImage)
          	{
          		temp.add(e.attr("abs:src"));
          	}
          	Database.pushInstructorImages(temp);
          }  
    }
    
    public void insertTables() throws Exception
    {
    


    	/*
    	
        String sql = "CREATE TABLE IF NOT EXISTS canvas" +
        	    "(course_descriptions` text NOT NULL, " +
        	    " course_categories text NOT NULL, " +
        	    " course_levels` text NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        */
      
        
       // for(int i = 0;i < images.size();i++)
       // {
        	
        	//stmt.executeUpdate("INSERT INTO Images " + 
        	//		"VALUES (, )"); 
       // }
      
    }
   
    public static void main(String[] args) throws Exception 
    {
    	Database d = new Database();
    	Scraper s = new Scraper();
    	s.scrapeCanvas();
    	s.insertTables();
    	
    	System.out.println(Database.toSql());
    }
}
