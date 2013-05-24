package com.example.ojscheluder;

import java.util.Date;
import java.util.Iterator;
import java.util.TimeZone;

import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
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
		try {
			String contesttime = jSchedule.getString("contesttime");
			String registrationtime = jSchedule.getString("registrationtime");
			
		} catch (JSONException e) {
			e.printStackTrace();
		}
		calMgr.createCalendarEntry("scheduler",
				"Registration is gonna start now", "topcoder",
				System.currentTimeMillis() + 100000,
				System.currentTimeMillis() + 2000000, false, true, 60);
	}

	JSONObject updateScheduleData(JSONObject oSchedule, JSONObject nSchedule,
			boolean isNewSchedule) {
		Iterator<String> iNewSchedule = nSchedule.keys();
		while (iNewSchedule.hasNext()) {
			String sContestType = iNewSchedule.next();
			try {
				JSONObject jNewContests = nSchedule.getJSONObject(sContestType);
				JSONObject jOldContests = null;
				if (!isNewSchedule) {
					if (!(isNewSchedule = oSchedule.isNull(sContestType))) {
						jOldContests = oSchedule.getJSONObject(sContestType);
					}
				}
				Iterator<String> iContests = jNewContests.keys();
				while (iContests.hasNext()) {
					String contest = iContests.next();
					JSONObject nContestSchedule = jNewContests
							.getJSONObject(contest);
					JSONObject oContestSchedule = null;
					int eventId;
					boolean needCrossCheck = !isNewSchedule;
					if (!isNewSchedule) {
						if (jOldContests.isNull(contest)) {
							needCrossCheck = false;
						} else {
							oContestSchedule = jOldContests
									.getJSONObject(contest);
						}
					}
					if (needCrossCheck
							&& isScheduleChanged(nContestSchedule,
									oContestSchedule)) {
						// updateCalendarEvent();
					} else {
						setCalendarEvent(nContestSchedule);
					}
				}
				return nSchedule; // check whether updated or not

			} catch (JSONException e) {
				e.printStackTrace();
			}
		}

		return null;
	}

	private boolean processContestSchedule(String sSchedule) {
		try {
			JSONObject jSchedule = new JSONObject(sSchedule);
			Iterator<String> keys = jSchedule.keys();
			while (keys.hasNext()) {
				String site = keys.next();
				String file = site + ".json";
				JSONObject nSchedule = jSchedule.getJSONObject(site);
				JSONObject oSchedule = StorageManager.getData("contests", file);
				boolean isNewSchedule = false;
				if (oSchedule == null) {
					isNewSchedule = true;
				}
				JSONObject updatedSchedule = updateScheduleData(nSchedule,
						oSchedule, isNewSchedule);
				if (!StorageManager
						.writeData(updatedSchedule, "contests", file)) {
					return false;
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
		try {
			if ((nSchedule.getString("contesttime")).equals(oSchedule
					.getString("contesttime"))) {
				if ((nSchedule.getString("registrationtime")).equals(oSchedule
						.getString("registrationtime"))) {
					return false;
				}
			}
		} catch (JSONException e) {
			e.printStackTrace();
		}
		return true;
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