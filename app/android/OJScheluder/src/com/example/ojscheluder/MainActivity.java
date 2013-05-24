package com.example.ojscheluder;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.os.Bundle;
import android.os.Environment;
import android.view.Menu;

public class MainActivity extends Activity {

	public static final String DATA_PATH = Environment
			.getExternalStorageDirectory().toString() + "/OJScheduler/";

	public static final String baseURL = "http://192.168.114.98/";

	private static final String TAG = "Scheduler.java";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		StorageManager.createRootDir();
		try {
			JSONObject jObj = new JSONObject();
			JSONArray contestNames = new JSONArray();
			contestNames.put("srm");
			jObj.put("Topcoder", contestNames);
			ContestManager cManager = new ContestManager(this);
			cManager.updateSchedule(jObj, baseURL);
		} catch (JSONException e) {
			e.printStackTrace();
		}
		setContentView(R.layout.activity_main);
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		getMenuInflater().inflate(R.menu.main, menu);
		return true;
	}
}