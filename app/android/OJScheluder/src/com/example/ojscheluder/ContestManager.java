package com.example.ojscheluder;

import java.util.Iterator;
import java.util.TimeZone;

import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.ContentResolver;
import android.content.ContentValues;
import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.provider.CalendarContract.Events;
import android.provider.CalendarContract.Reminders;
import android.util.Log;

import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;

public class ContestManager {

	public static String DATA_PATH = null;
	private final Activity activity;
	private static final String TAG = "ContestManager.java";
	private final CalendarManager calMgr;

	public ContestManager(Activity main) {
		this.activity = main;
		calMgr = new CalendarManager(activity);
	}

	AsyncHttpResponseHandler responseHandler = new AsyncHttpResponseHandler() {
		@Override
		public void onSuccess(String response) {
			Log.d(TAG, response);
			processContestSchedule(response);
		}

		@Override
		public void onFailure(Throwable e, String errorResponse) {
			Log.e(TAG, errorResponse.toString());
		}
	};

	void setCalendarEvent(JSONObject jSchedule) {
		
		Iterator<String> keys = (Iterator<String>) jSchedule.keys();
		while (keys.hasNext()) {
			String contestName = keys.next();
			try {
				JSONObject contests = jSchedule.getJSONObject(contestName);
				Iterator<String> iContests = (Iterator<String>) contests.keys();
				while (iContests.hasNext()) {
					String contest = iContests.next();
					JSONObject cSchedule = jSchedule.getJSONObject(contest);
					
				}
			} catch (JSONException e) {
					e.printStackTrace();
			}
		}		
		calMgr.createCalendarEntry("scheduler",
				"Registration is gonna start now", "topcoder",
				System.currentTimeMillis() + 100000,
				System.currentTimeMillis() + 2000000, false, true, 60);
	}

	private boolean processContestSchedule(String sSchedule) {
		try {
			JSONObject jSchedule = new JSONObject(sSchedule);
			Iterator<String> keys = (Iterator<String>) jSchedule.keys();
			while (keys.hasNext()) {
				String site = keys.next();
				String file = site + ".json";
				JSONObject nSchedule = jSchedule.getJSONObject(site);
				JSONObject oSchedule = StorageManager.getData("contests", file);
				if (oSchedule == null) {
					if (!StorageManager.writeData(nSchedule, "contests", file)) {
						return false;
					}
				} else {

					if (isScheduleChanged(oSchedule, nSchedule)) {
						
					}
				}

				
			}
			// need to write in such a way that it'll work for all
			// sites(iterate it)
			// change the schedule format so that the site name and data
			// can be get from the schedule
			// Log.v(TAG, oldSchedule.toString());
			// check old schedule with new if something gets changed, update it

			// update calendar

			// check data is correct or not and update calendar

		} catch (JSONException e) {
			e.printStackTrace();
		}
		return true;
	}

	private boolean isScheduleChanged(JSONObject oSchedule, JSONObject nSchedule) {
		// TODO Auto-generated method stub
		return false;
	}

	public void updateSchedule(JSONObject contests, String baseUrl) {
		Log.d(TAG, contests.toString());
		AsyncHttpClient client = new AsyncHttpClient();
		RequestParams params = new RequestParams();
		params.put("timeZone", TimeZone.getDefault().getID());
		params.put("contests", contests.toString());
		client.get(baseUrl + "Contests/getSchedule", params, responseHandler);
	}
}