/**
 * Screen scraping and database
 * insertion tool for the canvas.net
 * website.
 *
 * @author adrian
 * @version 1.0 2013/11/1
 */

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
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.Statement;

/**
 * A class to be used to parse website, scrape, and insert into a database.
 */
public class Scraper {
    private ArrayList<String> images;
    private ArrayList<String> courseNames;
    private ArrayList<String> startDates;
    private ArrayList<String> courseLengths;
    private ArrayList<String> coursePages;
    private ArrayList<String> professors;
    private ArrayList<String> instructorImages;

    /**
     * Scraper default constructor.
     */
    public Scraper() {
        images = new ArrayList<String>();
        courseNames = new ArrayList<String>();
        startDates = new ArrayList<String>();
        courseLengths = new ArrayList<String>();
        coursePages = new ArrayList<String>();
        professors = new ArrayList<String>();
        instructorImages = new ArrayList<String>();
    }

    /**
     * Scrapes the canvas.net site for
     * specific data and stores it in arraylists.
     * @throws Exception Throws when the connection is bad.
     */
    public final void scrapeCanvas() throws Exception {
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
            coursePages.add(link.attr("abs:href"));

            // get image and store
            Element image = coursePage.select(
                    "div[class=featured-course-image] span")
                    .get(0);
            String attr = image.attr("style");

            images.add(url + attr.substring(
                    attr.indexOf("/"), attr.indexOf(")")));

            // get course name and store
            Elements courseName = coursePage.select(
                    "h2[class=emboss-light]");
            courseNames.add(courseName.text());

            // start date and store
            Element startDate = coursePage.select(
                    "div[class=course-detail-info]"
                    + " > p > strong").get(0);
            String s = startDate.text();

            boolean endDefined = true;
            String selfPacedPattern = "Self-paced, available (.*)";
            if (s.matches(selfPacedPattern)) {
                s = s.replaceAll(selfPacedPattern, "$1");
                endDefined = false;
            }
            startDates.add(s);

            // get course length
            if (endDefined) {
                // get end date
                Element endDate = coursePage.select(
                        "div[class=course-detail-info]"
                        + " > p > strong").get(1);
                String ed = endDate.text();

                // convert from string to date type
                SimpleDateFormat dt = new SimpleDateFormat(
                        "MMM dd, yyyy");
                Date date1 = dt.parse(s);
                Date date2 = dt.parse(ed);

                // weeks test
                DateTime dateTime1 = new DateTime(date1);
                DateTime dateTime2 = new DateTime(date2);

                s = Integer.toString(Weeks.weeksBetween(
                        dateTime1, dateTime2).
                        getWeeks());

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
                    .select("div[class=instructor-bio]"
                            + " img");
            instructorImages.add(instructorImage.attr("abs:src"));
        }
    }

    /**
     * Inserts stored canvas.net data into
     * a table in the specified database.
     * @throws Exception
     *             throws if cannot connect to the database.
     */
    public final void insertTables() throws Exception {
        Class.forName("com.mysql.jdbc.Driver");

        String url = "jdbc:mysql://localhost:3306/courses";
        Connection conn = DriverManager.getConnection(url, "root", "");

        Statement stmt = conn.createStatement();

        String s = "CREATE TABLE IF NOT EXISTS canvas "
                + "(`Course Pages` TEXT, `Images` TEXT, "
                + "`Course Names` TEXT, `Start Dates` TEXT, "
                + "`Course Lengths` TEXT, `Professors` TEXT, "
                + "`Instructor Images` TEXT, " + "`Site` TEXT)";

        stmt.executeUpdate(s);

        PreparedStatement preparedStatement = conn
                .prepareStatement("insert into canvas values "
                        + "(?, ?, ?, ?, ?, ?, ?, ?)");

        for (int i = 0; i < courseNames.size(); i++) {
            preparedStatement.setString(1, coursePages.get(i));
            preparedStatement.setString(2, images.get(i));
            preparedStatement.setString(3, courseNames.get(i));
            preparedStatement.setString(4, startDates.get(i));
            preparedStatement.setString(5, courseLengths.get(i));
            preparedStatement.setString(6, professors.get(i));
            preparedStatement.setString(7, instructorImages.get(i));
            preparedStatement.setString(8, "Canvas");
            preparedStatement.executeUpdate();
        }

        conn.close();
    }

    /**
     * The main method.
     * @param args Command-line arguments.
     * @throws Exception
     *             Throws if bad.
     */
    public static void main(final String[] args) throws Exception {
        Scraper s = new Scraper();
        s.scrapeCanvas();
        s.insertTables();
    }
}
