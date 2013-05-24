package com.example.ojscheluder;

public class Messages {
	public static final String REGISTRATION_START = "Registration for";

	public static String getRegistrationStartMessage(String site, String contestName, long timeInMin) {
		return "Registration for " + site + " " + contestName +" is gonna start in " + timeInMin
				+ "minutes";
	}
	
	public static String getContestMessageInMin(String site,  String contestName, long timeInMin) {
		return "Registration for " + site + " " + contestName + "is gonna start in " + timeInMin
				+ "minutes";
	}
	
	public static String getContestMessageInHours(String site,  String contestName, long timeInHours) {
		return "Registration for " + site + " " + contestName + "is gonna start in " + timeInHours
				+ "hours";
	}
}
