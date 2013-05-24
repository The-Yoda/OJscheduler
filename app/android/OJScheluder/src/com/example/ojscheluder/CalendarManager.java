package com.example.ojscheluder;

import java.util.TimeZone;

import android.app.Activity;
import android.content.ContentResolver;
import android.content.ContentUris;
import android.content.ContentValues;
import android.database.Cursor;
import android.net.Uri;
import android.provider.CalendarContract.Events;
import android.provider.CalendarContract.Reminders;
import android.util.Log;

public class CalendarManager {

	private static final String DEBUG_TAG = "CalendarManager";
	private static Activity activity;
	private static int EVENTID = 0;

	public CalendarManager(Activity main) {
		activity = main;
	}

	public int createCalendarEntry(String title, String description,
			String location, long startTime, long endTime, boolean allDay,
			boolean hasAlarm, int selectedReminderValue) {
		EVENTID++;
		ContentResolver cr = activity.getContentResolver();
		ContentValues values = new ContentValues();
		values.put(Events.DTSTART, startTime);
		values.put(Events.DTEND, endTime);
		values.put(Events.TITLE, title);
		values.put(Events.DESCRIPTION, description);
		values.put(Events.CALENDAR_ID, EVENTID);

		if (allDay) {
			values.put(Events.ALL_DAY, true);
		}

		if (hasAlarm) {
			values.put(Events.HAS_ALARM, true);
		}

		values.put(Events.EVENT_TIMEZONE, TimeZone.getDefault().getID());
		Uri uri = cr.insert(Events.CONTENT_URI, values);
		Log.i(DEBUG_TAG, "Uri for calendar entry" + uri.toString());
		long eventID = Long.parseLong(uri.getLastPathSegment());

		if (hasAlarm) {
			ContentValues reminders = new ContentValues();
			reminders.put(Reminders.EVENT_ID, eventID);
			reminders.put(Reminders.METHOD, Reminders.METHOD_ALERT);
			reminders.put(Reminders.MINUTES, selectedReminderValue);
			Uri uri2 = cr.insert(Reminders.CONTENT_URI, reminders);
			Log.i(DEBUG_TAG, "uri2" + uri2);
		}
		return EVENTID;
	}

	private int UpdateCalendarEntry(int entryID) {
		int iNumRowsUpdated = 0;

		ContentValues event = new ContentValues();

		event.put("title", "Changed Event Title");
		event.put("hasAlarm", 1); // 0 for false, 1 for true

		Uri eventsUri = Uri.parse(getCalendarUriBase() + "events");
		Uri eventUri = ContentUris.withAppendedId(eventsUri, entryID);

		iNumRowsUpdated = activity.getContentResolver().update(eventUri, event,
				null, null);

		Log.i(DEBUG_TAG, "Updated " + iNumRowsUpdated + " calendar entry.");

		return iNumRowsUpdated;
	}

	private int DeleteCalendarEntry(int entryID) {
		int iNumRowsDeleted = 0;

		Uri eventsUri = Uri.parse(getCalendarUriBase() + "events");
		Uri eventUri = ContentUris.withAppendedId(eventsUri, entryID);
		iNumRowsDeleted = activity.getContentResolver().delete(eventUri, null,
				null);

		Log.i(DEBUG_TAG, "Deleted " + iNumRowsDeleted + " calendar entry.");

		return iNumRowsDeleted;
	}

	private String getCalendarUriBase() {
		String calendarUriBase = null;
		Uri calendars = Uri.parse("content://calendar/calendars");
		Cursor managedCursor = null;
		try {
			managedCursor = activity.managedQuery(calendars, null, null, null,
					null);
		} catch (Exception e) {
			// eat
		}

		if (managedCursor != null) {
			calendarUriBase = "content://calendar/";
		} else {
			calendars = Uri.parse("content://com.android.calendar/calendars");
			try {
				managedCursor = activity.managedQuery(calendars, null, null,
						null, null);
			} catch (Exception e) {
				// eat
			}

			if (managedCursor != null) {
				calendarUriBase = "content://com.android.calendar/";
			}

		}

		return calendarUriBase;
	}

}
