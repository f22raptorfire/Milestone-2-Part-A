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
import java.sql.*;

public class Scraper {
	private ArrayList<String> images;
	private ArrayList<String> courseNames;
	private ArrayList<String> startDates;
	private ArrayList<String> courseLengths;
	private ArrayList<String> professors;
	private ArrayList<String> instructorImages;

	public Scraper() {
		images = new ArrayList<String>();
		courseNames = new ArrayList<String>();
		startDates = new ArrayList<String>();
		courseLengths = new ArrayList<String>();
		;
		professors = new ArrayList<String>();
		instructorImages = new ArrayList<String>();

	}

	public void scrapeCanvas() throws Exception {
		String url = "http://canvas.net";
		Document homePage = Jsoup.connect(url).get();

		// get course link elements
		Elements courseLinks = homePage
				.select(".btn.btn-action.learn-more-button");

		// data structures to store content
		Document coursePage;

		// get content from each course page
		for (Element link : courseLinks) {
			// get page contents
			coursePage = Jsoup.connect(link.attr("href")).get();

			// get image and store
			Element image = coursePage.select(
					"div[class=featured-course-image] span").get(0);
			String attr = image.attr("style");

			// Database.pushImage(url + attr.substring( attr.indexOf("/"),
			// attr.indexOf(")")));
			images.add(url
					+ attr.substring(attr.indexOf("/"), attr.indexOf(")")));

			// get course name and store
			Elements courseName = coursePage.select("h2[class=emboss-light]");
			courseNames.add(courseName.text());

			// start date and store
			Element startDate = coursePage.select(
					"div[class=course-detail-info] > p > strong").get(0);
			String s = startDate.text();

			String r;
			boolean endDefined = true;
			if (s.matches("Self-paced, available.*")) {
				String startDateRegex = "(Self-paced, available )?(.*)";
				r = "$2";
				s = s.replaceAll(startDateRegex, r);
				endDefined = false;
			}
			startDates.add(s);

			// get course length
			if (endDefined) {
				// get end date
				Element endDate = coursePage.select(
						"div[class=course-detail-info] > p > strong").get(1);
				String ed = endDate.text();
				String endDateRegex = "(.*)";
				r = "$1";

				s = s.replaceAll(endDateRegex, r);

				// convert from string to date type
				SimpleDateFormat dt = new SimpleDateFormat("MMM dd, yyyy");
				Date date1 = dt.parse(s);
				Date date2 = dt.parse(ed);

				// weeks test
				DateTime dateTime1 = new DateTime(date1);
				DateTime dateTime2 = new DateTime(date2);

				s = Integer.toString(Weeks.weeksBetween(dateTime1, dateTime2)
						.getWeeks());

			} else {
				s = "indefinite";
			}

			courseLengths.add(s);

			// get professor name and store
			Elements professor = coursePage
					.select("div[class=instructor-bio] h3");
			professors.add(professor.text());

			// get instructor image links and store
			Elements instructorImage = coursePage
					.select("div[class=instructor-bio] img");
			//System.out.println("=" + instructorImage + "=");
			instructorImages.add(instructorImage.attr("abs:src"));
			
		}
	}

	public void insertTables() throws Exception {
		// This will load the MySQL driver, each DB has its own driver
		Class.forName("com.mysql.jdbc.Driver");

		String url = "jdbc:mysql://localhost:3306/courses";
		Connection conn = DriverManager.getConnection(url, "root", "");

		Statement stmt = conn.createStatement();

	    String s = "CREATE TABLE IF NOT EXISTS canvas " +
	                   "(`Images` TEXT, " +
	                   "`Course Names` TEXT, " + 
	                   "`Start Dates` TEXT, " + 
	                   "`Course Lengths` TEXT, " + 
	                   "`Professors` TEXT, " +
	                   "`Instructor Images` TEXT, " +
	    			   "`Site` TEXT)"; 

	      stmt.executeUpdate(s);
		
		 // PreparedStatements can use variables and are more efficient
		PreparedStatement preparedStatement = conn.prepareStatement("insert into canvas values (?, ?, ?, ?, ?, ?, ?)");
	      // "myuser, webpage, datum, summary, COMMENTS from FEEDBACK.COMMENTS");
	      // Parameters start with 1
		
		for(int i = 0; i < courseNames.size();i++)
		{
	      preparedStatement.setString(1, images.get(i));
	      preparedStatement.setString(2, courseNames.get(i));
	      preparedStatement.setString(3, startDates.get(i));
	      preparedStatement.setString(4, courseLengths.get(i));
	      preparedStatement.setString(5, professors.get(i));
	      preparedStatement.setString(6, instructorImages.get(i)); 
	      preparedStatement.setString(7, "Canvas");
	      preparedStatement.executeUpdate();
		}

		
		conn.close();
	}

	public static void main(String[] args) throws Exception {
		Scraper s = new Scraper();
		s.scrapeCanvas();
		s.insertTables();

	}
}
