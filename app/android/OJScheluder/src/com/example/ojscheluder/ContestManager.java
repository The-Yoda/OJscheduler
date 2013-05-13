package com.example.ojscheluder;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.os.Environment;
import android.util.Log;

import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;

public class ContestManager {

	public static String DATA_PATH = null;

	public static String baseURL = null;

	private static final String TAG = "ContestManager.java";

	AsyncHttpResponseHandler responseHandler = new AsyncHttpResponseHandler() {
		@Override
		public void onSuccess(String response) {
			Log.d("response", response);
			ArrayList<String> sites = new ArrayList<String>();
			sites.add("Topcoder");
			processContestSchedule(response, sites);
			System.out.println(response);
		}

		@Override
		public void onFailure(Throwable e, String errorResponse) {
			Log.e("error", errorResponse.toString());
		}

	};

	private void processContestSchedule(String sSchedule, ArrayList<String> site) {
		try {
			JSONObject jSchedule = new JSONObject(sSchedule);

			// need to write in such a way that it'll work for all
			// sites(iterate it)
			// change the schedule format so that the site name and data
			// can be get from the schedule
			JSONObject oldSchedule = getData("contests", "Topcoder");
			// check old schedule with new if something gets changed, update it
			writeData(sSchedule, "contests", "Topcoder");
			// update calendar

			// check data is correct or not and update calendar

		} catch (JSONException e) {
			e.printStackTrace();
		}
	}

	private boolean writeData(String sSchedule, String type, String site) {
		File dir = new File(DATA_PATH + type + "/");

		if (!dir.exists()) {
			if (!dir.mkdirs()) {
				Log.v(TAG, "ERROR: Creation of directory " + DATA_PATH + type
						+ " on sdcard failed");
				return false;
			}
		}
		try {
			BufferedWriter br = new BufferedWriter(new FileWriter(DATA_PATH
					+ type + "/" + site + ".json"));
			br.write(sSchedule);
			br.close();
			Log.v(TAG, "data written successfully to " + DATA_PATH + type + "/"
					+ site + ".json");
			return true;
		} catch (IOException e) {
			Log.e(TAG, "Was unable to copy" + e.toString());
			return false;
		}
	}

	private JSONObject getData(String type, String site) {
		try {
			BufferedReader br = new BufferedReader(new FileReader(DATA_PATH
					+ type + "/" + site + ".json"));
			String line = "", data = "";
			while ((line = br.readLine()) != null) {
				data += line;
			}
			br.close();
			return new JSONObject(data);
		} catch (Exception e) {
			e.printStackTrace();
		}
		return null;
	}

	public void updateSchedule(HashMap<String, ArrayList<String>> sites,
			String baseUrl, String dataPath) {
		baseURL = baseUrl;
		DATA_PATH = dataPath;
		AsyncHttpClient client = new AsyncHttpClient();
		for (String site : sites.keySet()) {
			JSONArray contestList = new JSONArray(sites.get(site));
			RequestParams params = new RequestParams();
			params.put("timeZone", "UTC");
			params.put("contests", contestList.toString());
			client.get(baseURL + "Contests/" + site + "/getSchedule", params,
					responseHandler);
		}
	}
}