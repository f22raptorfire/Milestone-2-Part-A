package data_scraping;

import java.util.ArrayList;

public class Database {
	static private ArrayList<String> images;
	static private ArrayList<String> courseNames;
	static private ArrayList<String> startDates;
	static private ArrayList<String> courseLengths;
	static private ArrayList<String> professors;
	static private ArrayList<ArrayList<String>> instructorImages;
    
    public Database()
    {
    	images = new ArrayList<String>();
    	courseNames = new ArrayList<String>();
        startDates = new ArrayList<String>();
        courseLengths = new ArrayList<String>();
        professors = new ArrayList<String>();
        instructorImages = new ArrayList<ArrayList<String>>();
    }
    
    static void pushImage(String i) {
    	System.out.println("img: ("+i+")");
    	System.out.println();
    	
    	images.add(i);
    }
    
    static void pushCourseName(String c) {
    	courseNames.add(c);
    }
    
    static void pushStartDate(String d) {
    	startDates.add(d);
    }
    
    static void pushCourseLength(String cd) {
    	courseLengths.add(cd);
    }
    
    static void pushProfessor(String p) {
    	professors.add(p);
    }
    
    static void pushInstructorImages(ArrayList<String> i) {
    	instructorImages.add(i);
    }
    
    static String toSql() {
    	String tmp = "";
    	for(int i = 0; 
    			(i < images.size() && 
    			i <	courseNames.size() &&
    			i < startDates.size() && 
    			i < courseLengths.size() && 
    			i < professors.size() &&
    			i < instructorImages.size()); 
    			i++) {
    		tmp += "INSERT INTO `table` VALUES(";
    		tmp += "'" + images.get(i);
    		tmp += "','";
    		tmp += courseNames.get(i);
    		tmp += "','";
    		tmp += startDates.get(i);
    		tmp += "','";
    		tmp += courseLengths.get(i);
    		tmp += "','";
    		tmp += professors.get(i);
    		tmp += "','";
    		tmp += instructorImages.get(i) + "'";
    		tmp += ")\n";
    	}
    	
    	return tmp;
    }
}
