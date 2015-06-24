package com.bizarre.dingdatt;

import java.util.ArrayList;

import org.json.JSONObject;

import com.bizarre.dingdatt.strings.CommonConfig;
import com.bizarre.dingdatt.strings.LocalData;
import com.bizarre.dingdatt.strings.StringURLs;

import android.app.ActionBar;
import android.app.Activity;
import android.content.res.Configuration;
import android.os.Bundle;
import android.support.v4.app.ActionBarDrawerToggle;
import android.support.v4.app.FragmentActivity;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.view.Gravity;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.Toast;

public class NotificationActivity extends FragmentActivity {
	private DrawerLayout mDrawerLayout;
	private ListView mDrawerList;
	private ActionBarDrawerToggle mDrawerToggle;

	private CharSequence mDrawerTitle;
	private CharSequence mTitle;
	private String[] mPlanetTitles;
	ImageView imageView122;
	public static Activity notification;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_notification);

		NotificationCount();

		notification = this;

		mTitle = mDrawerTitle = getTitle();
		mPlanetTitles = getResources().getStringArray(R.array.menu_list);
		mDrawerLayout = (DrawerLayout) findViewById(R.id.drawer_layout);
		mDrawerList = (ListView) findViewById(R.id.left_drawer);

		// set a custom shadow that overlays the main content when the drawer
		// opens
		mDrawerLayout.setDrawerShadow(R.drawable.drawer_shadow,
				GravityCompat.START);
		// set up the drawer's list view with items and click listener

		mPlanetTitles = Main.UpdateDrawerlist(this, mPlanetTitles);

		mDrawerList.setAdapter(new ArrayAdapter<String>(this,
				R.layout.drawer_list_item, mPlanetTitles));
		mDrawerList.setOnItemClickListener(new DrawerItemClickListener());

		// enable ActionBar app icon to behave as action to toggle nav drawer
		getActionBar().setDisplayHomeAsUpEnabled(true);
		getActionBar().setHomeButtonEnabled(true);

		// ActionBarDrawerToggle ties together the the proper interactions
		// between the sliding drawer and the action bar app icon
		mDrawerToggle = new ActionBarDrawerToggle(this, /* host Activity */
		mDrawerLayout, /* DrawerLayout object */
		R.drawable.ic_drawer, /* nav drawer image to replace 'Up' caret */
		R.string.open, /* "open drawer" description for accessibility */
		R.string.close /* "close drawer" description for accessibility */
		) {
			public void onDrawerClosed(View view) {
				getActionBar().setTitle(mTitle);
				invalidateOptionsMenu(); // creates call to
											// onPrepareOptionsMenu()
			}

			public void onDrawerOpened(View drawerView) {
				getActionBar().setTitle(mDrawerTitle);
				invalidateOptionsMenu(); // creates call to
											// onPrepareOptionsMenu()
			}
		};

		mDrawerLayout.setDrawerListener(mDrawerToggle);

		if (savedInstanceState == null) {
			// update the main content by replacing fragments
			getSupportFragmentManager()
					.beginTransaction()
					.replace(R.id.content_frame, new NotificationFragment(this))
					.commit();
		}

		getActionBar().setDisplayOptions(ActionBar.DISPLAY_SHOW_CUSTOM);
		getActionBar().setCustomView(R.layout.abs_layout);

		imageView122 = (ImageView) getActionBar().getCustomView().findViewById(
				R.id.menudrawer);

		Main.SetTitle(getActionBar().getCustomView(), CommonConfig.NOTIFICATION);

		imageView122.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				if (mDrawerLayout.isDrawerVisible(Gravity.LEFT)) {
					mDrawerLayout.closeDrawers();
				} else {
					mDrawerLayout.openDrawer(Gravity.LEFT);
				}
			}
		});
	}

	private void NotificationCount() {
		try {

			String url = StringURLs.NOTIFICATION_COUNT;

			ArrayList<String> names = new ArrayList<String>();
			ArrayList<String> values = new ArrayList<String>();

			names.add("user_id");
			LocalData data = new LocalData(NotificationActivity.this);
			values.add(data.GetS("userid"));
			ConnectServerImage connectServerImage = new ConnectServerImage();
			connectServerImage.setContext(NotificationActivity.this);
			connectServerImage.setListener(new ConnectServerListener() {

				@Override
				public void onServerResponse(String sJSON, JSONObject jsonObject) {
					// TODO Auto-generated method stub

					try {
						if (sJSON.length() > 0) {

							JSONObject jsonObject2 = new JSONObject(sJSON);

							LocalData data = new LocalData(
									NotificationActivity.this);
							data.Update("notificationcount",
									jsonObject2.getInt("admincount")
											+ jsonObject2.getInt("membercount"));
						} else {

							Toast.makeText(
									NotificationActivity.this,
									Main.getStringResourceByName(
											NotificationActivity.this, "c100"),
									Toast.LENGTH_LONG).show();
						}
					} catch (Exception exp) {

						Toast.makeText(
								NotificationActivity.this,
								Main.getStringResourceByName(
										NotificationActivity.this, "c100"),
								Toast.LENGTH_LONG).show();
					}
				}
			});
			connectServerImage.setMode(ConnectServerImage.MODE_POST);
			connectServerImage.setNames(names);
			connectServerImage.setValues(values);
			connectServerImage.execute(url);
		} catch (Exception exp) {

			Toast.makeText(
					NotificationActivity.this,
					Main.getStringResourceByName(NotificationActivity.this,
							"c100"), Toast.LENGTH_LONG).show();
		}
	}

	/*
	 * @Override public boolean onCreateOptionsMenu(Menu menu) { MenuInflater
	 * inflater = getMenuInflater();
	 * inflater.inflate(R.menu.contest_list_sample, menu); return
	 * super.onCreateOptionsMenu(menu); }
	 * 
	 * Called whenever we call invalidateOptionsMenu()
	 * 
	 * @Override public boolean onPrepareOptionsMenu(Menu menu) { // If the nav
	 * drawer is open, hide action items related to the content view boolean
	 * drawerOpen = mDrawerLayout.isDrawerOpen(mDrawerList);
	 * menu.findItem(R.id.action_websearch).setVisible(!drawerOpen); return
	 * super.onPrepareOptionsMenu(menu); }
	 * 
	 * @Override public boolean onOptionsItemSelected(MenuItem item) { // The
	 * action bar home/up action should open or close the drawer. //
	 * ActionBarDrawerToggle will take care of this. if
	 * (mDrawerToggle.onOptionsItemSelected(item)) { return true; } // Handle
	 * action buttons switch(item.getItemId()) { case R.id.action_websearch: //
	 * create intent to perform web search for this planet Intent intent = new
	 * Intent(Intent.ACTION_WEB_SEARCH); intent.putExtra(SearchManager.QUERY,
	 * getActionBar().getTitle()); // catch event that there's no activity to
	 * handle intent if (intent.resolveActivity(getPackageManager()) != null) {
	 * startActivity(intent); } else { Toast.makeText(this, "Not found",
	 * Toast.LENGTH_LONG).show(); } return true; default: return
	 * super.onOptionsItemSelected(item); } }
	 */

	/* The click listner for ListView in the navigation drawer */
	private class DrawerItemClickListener implements
			ListView.OnItemClickListener {
		@Override
		public void onItemClick(AdapterView<?> parent, View view, int position,
				long id) {
			selectItem(position);
		}
	}

	private void selectItem(int position) {

		Main.MenuList(this, position);

		mDrawerList.setItemChecked(position, true);
		setTitle(mPlanetTitles[position]);
		mDrawerLayout.closeDrawer(mDrawerList);
	}

	@Override
	public void setTitle(CharSequence title) {
		mTitle = title;
		getActionBar().setTitle(mTitle);
	}

	/**
	 * When using the ActionBarDrawerToggle, you must call it during
	 * onPostCreate() and onConfigurationChanged()...
	 */

	@Override
	protected void onPostCreate(Bundle savedInstanceState) {
		super.onPostCreate(savedInstanceState);
		// Sync the toggle state after onRestoreInstanceState has occurred.
		mDrawerToggle.syncState();
	}

	@Override
	public void onConfigurationChanged(Configuration newConfig) {
		super.onConfigurationChanged(newConfig);
		// Pass any configuration change to the drawer toggls
		mDrawerToggle.onConfigurationChanged(newConfig);
	}
}